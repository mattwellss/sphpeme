<?php

namespace Sphpeme;

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
            $list = [];
            while (true) {
                $token = $this->nextToken();
                if ($token === ')') {
                    return $list;
                }

                $list[] = $this->readAhead($token);
            }
        }


        if (isset($this->quotes[$token])) {
            return [$this->quotes[$token], $this->read()];
        }

        return atom($token);
    }

    public function read()
    {
        $first = $this->nextToken();

        return $first !== false
            ? $this->readAhead($first)
            : false;
    }

    /**
     * @return bool|string
     */
    public function nextToken()
    {
        while (true) {
            if (trim($this->line) === '') {
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
            // @codeCoverageIgnoreStart
        }
    }
// @codeCoverageIgnoreEnd

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
