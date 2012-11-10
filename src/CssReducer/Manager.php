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

use CssReducer\Log\LoggerInterface;


class Manager
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function reduce($css)
    {
        $parser = new Parser();
        $parsedCss = $parser->parse($css);

        $optimizer = new Optimizer($this->logger);
        $optimizedCss = $optimizer->build($parsedCss);

        $cssGenerator = new Generator\Css($this->logger);
        $generatedCss = $cssGenerator->generate($optimizedCss);

        $cssMinifier = new Minifier($this->logger);
        $minifiedCss = $cssMinifier->minify($generatedCss, array(
            'remove_comments' => true,
            'remove_whitespaces' => true,
            'remove_tabs' => true,
            'remove_newlines' => true,
        ));

        return $minifiedCss;
    }

    public function diff($originalCss, $modifiedCss)
    {

    }
}
