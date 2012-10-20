<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Test;

use CssReducer\Parser;


class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleCss()
    {

        $css = "body {
                background: #ffffff;
              color: red
            }
            ";


        $p = new Parser();

        $parsedCss = $p->parse($css);

        $this->assertEquals(1, count($parsedCss));
        $this->assertEquals(true, array_key_exists('body', $parsedCss[0]));
        $this->assertEquals(true, array_key_exists('background', $parsedCss[0]['body']));
        $this->assertEquals(true, array_key_exists('color', $parsedCss[0]['body']));
        $this->assertEquals('#ffffff', $parsedCss[0]['body']['background']);
    }
}
