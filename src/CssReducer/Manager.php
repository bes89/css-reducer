<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer;


class Manager
{
    public function reduce($css)
    {
        $parser = new Parser();
        $parsedCss = $parser->parse($css);

        $optimizer = new Optimizer();
        $optimizedCss = $optimizer->build($parsedCss);

        $cssGenerator = new \CssReducer\Generator\Css();
        $generatedCss = $cssGenerator->generate($optimizedCss);

        return $generatedCss;
    }
}
