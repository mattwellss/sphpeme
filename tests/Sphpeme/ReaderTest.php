<?php
/**
 * User: mpw
 * Date: 2017-12-04 21:37 PM
 */

namespace Sphpeme;

use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructRequiresStream()
    {
        $notStream = false;
        new Reader($notStream);
    }

//    public function testRead()
//    {
//        $res = fopen('php://memory', 'w+b');
//        fwrite($res, <<<SCHEME
//(define value (apply + '(1 2 3)))
//SCHEME
//        );
//        rewind($res);
//
//        $expectation = [
//            Symbol::make('define'),
//            Symbol::make('value'),
//            [
//                Symbol::make('apply'),
//                Symbol::make('+'),
//                [
//                    Symbol::make('quote'),
//                    [
//                        new Scalar(1),
//                        new Scalar(2),
//                        new Scalar(3),
//                    ]
//                ]
//            ]
//        ];
//
//        $reader = new Reader($res);
//        $out = $reader->read();
//        fclose($res);
//
//        static::assertEquals($expectation, $out);
//    }

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
        $reader = new Reader($res);
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
        $reader = new Reader($res);
        $reader->read();
        fclose($res);
    }

    public function testQuote()
    {
        $expectation = [
            Symbol::make('quote'),
            [
                new Scalar(1)
            ]
        ];

        $res = fopen('php://memory', 'w+b');
        fwrite($res, <<<SCHEME
'(1)
SCHEME
        );
        rewind($res);

        $reader = new Reader($res);
        $out = $reader->read();
        fclose($res);

        static::assertEquals($expectation, $out);
    }
}
