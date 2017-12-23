<?php

namespace Sphpeme;

use PHPUnit\Runner\Exception;

class Reader
{
    private $tokenizeRegexp = '/\s*(,@|[(\'`,)]|"(?:[\\].|[^])+"|;.+|[^\s(\'`,;)]+)(.*)/';
    private $file;
    private $line = '';

    private $quotes;

    public static function fromStream($stream)
    {
        if (!\is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new \InvalidArgumentException('Must give me a file stream');
        }

        return new static($stream);
    }

    public static function fromFilepath($filepath)
    {
        if (!file_exists($filepath)) {
            throw new \InvalidArgumentException("{$filepath} does not exist or is not readable.");
        }

        return new static(fopen($filepath, 'r+b'));
    }

    private function __construct($file)
    {
        $this->quotes = [
            '\'' => Symbol::make('quote'),
            '`' => Symbol::make('quasiquote'),
            ',' => Symbol::make('unquote'),
            ',@' => Symbol::make('unquote-splicing'),
        ];

        $this->file = $file;
    }

    /**
     * @param $token
     * @return array|mixed
     * @throws \Exception
     */
    private function readAhead($token)
    {
        $this->guardAgainstUnexpectedEof($token);
        $this->guardAgainstInvalidExpression($token);

        if ($token === '(') {
            $l = [];
            while (true) {
                $token = $this->nextToken();
                if ($token === ')') {
                    return $l;
                }

                $l[] = $this->readAhead($token);
            }
        }


        if (isset($this->quotes[$token])) {
            return [$this->quotes[$token], $this->read()];
        }

        return atom($token);
    }

    private function readExp($token)
    {
        $this->guardAgainstUnexpectedEof($token);

        if ($token === '(') {
            return Pair::list($this->readExp($this->nextToken()));
        }

        if ($token === ')') {
            return null;
        }

        $read = $this->readExp($this->nextToken());

        if ($read === null) {
            return Pair::list(atom($token));
        }

        if (isset($this->quotes[$token])) {
            return Pair::cons($this->quotes[$token], $read);
        }

        return Pair::cons(atom($token), $read);
    }

    public function read()
    {
        $first = $this->nextToken();

        $exp = $first !== false
            ? $this->readExp($first)
            : false;

        if ($exp) {
            return Pair::cons($exp, $this->read());
        }

        return $exp;
    }

    /**
     * @return bool|string
     */
    public function nextToken()
    {
        while (true) {
            if ($this->line === '') {
                $this->line = fgets($this->file);
            }

            if ($this->line === false) {
                return $this->line;
            }

            preg_match($this->tokenizeRegexp, $this->line, $matches);

            list($_, $token, $this->line) = $matches;

            if ($token !== ';' || $token !== '') {
                return $token;
            }
        }
    }

    /**
     * @param $token
     * @return null
     * @throws \Exception
     */
    private function guardAgainstUnexpectedEof($token)
    {
        if ($token === false) {
            throw new \Exception('unexpected eof!');
        }
    }

    /**
     * @param $token
     * @return null
     * @throws \Exception
     */
    private function guardAgainstInvalidExpression($token)
    {
        if ($token === ')') {
            throw new \Exception('unexpected end of expression');
        }
    }
}