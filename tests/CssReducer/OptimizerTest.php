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

use CssReducer\Optimizer;
use CssReducer\Parser;

class OptimizerTest extends \PHPUnit_Framework_TestCase
{
    public function testNoOptimization()
    {
        $css = "body { color: #ffffff; }";

        $p = new Parser();
        $o = new Optimizer();

        $parsedCss = $p->parse($css);
        $optimizedCss = $o->build($parsedCss);

        $this->assertEquals(1, count($optimizedCss));

        $selector = $optimizedCss[0];
        $properties = $selector->getProperties();

        $this->assertEquals('body', $selector->getName());
        $this->assertEquals(1, count($properties));
        $this->assertTrue($properties[0] instanceof \CssReducer\Css\Property\Color);
    }
}
