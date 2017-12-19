<?php

namespace Sphpeme;

use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadStreamInput()
    {
        $notStream = false;
        Reader::fromStream($notStream);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadFileInput()
    {
        $notAfile = tmpfile();
        $name = stream_get_meta_data($notAfile)['uri'];
        fclose($notAfile);
        Reader::fromFilepath($name);
    }

    /**
     * @expectedException \Exception
     */
    public function testUnexpectedEof()
    {
        $res = fopen('php://memory', 'w+b');
        fwrite($res, <<<SCHEME
'(1
asdf

SCHEME
        );
        rewind($res);
        $reader = Reader::fromStream($res);
        $out = $reader->read();
        fclose($res);
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidExpression()
    {
        $res = fopen('php://memory', 'w+b');
        fwrite($res, <<<SCHEME
)
SCHEME
        );
        rewind($res);
        $reader = Reader::fromStream($res);
        $reader->read();
        fclose($res);
    }

    public function testQuote()
    {
        $expectation = Pair::list(
            Symbol::make('quote'),
            Pair::list(new Scalar(1)));

        $res = fopen('php://memory', 'w+b');
        fwrite($res, <<<SCHEME
'(1)
SCHEME
        );
        rewind($res);

        $reader = Reader::fromStream($res);
        $out = $reader->read();
        fclose($res);

        static::assertEquals($expectation, $out);
    }

    public function testReadFile()
    {
        $file = __DIR__ . '/../../examples/fib.scm';
        static::assertInstanceOf(Reader::class, Reader::fromFilepath($file));
    }
}
