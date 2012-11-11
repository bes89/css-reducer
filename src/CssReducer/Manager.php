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
     * @var string
     */
    protected $originalCss;

    /**
     * @var string
     */
    protected $modifiedCss;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $css
     * @return string
     */
    public function reduce($css)
    {
        $this->originalCss = $css;

        $parser = new Parser();
        $parsedCss = $parser->parse($css);

        $optimizer = new Optimizer($this->logger);
        $optimizedCss = $optimizer->build($parsedCss);

        $cssGenerator = new Generator\Css($this->logger);
        $generatedCss = $cssGenerator->generate($optimizedCss);

        $this->modifiedCss = $generatedCss;

        $cssMinifier = new Minifier($this->logger);
        $minifiedCss = $cssMinifier->minify($generatedCss, array(
            'remove_comments' => true,
            'remove_whitespaces' => true,
            'remove_tabs' => true,
            'remove_newlines' => true,
        ));

        return $minifiedCss;
    }

    /**
     * @return string
     */
    public function getDiff()
    {
        $options = array(
            'ignoreWhitespace' => true,
            'ignoreCase' => true,
        );

        $a = $this->originalCss;
        $b = $this->modifiedCss;

        $a = explode("\n", $this->format($a));
        $b = explode("\n", $this->format($b));

        $diff = new \Diff($a, $b, $options);

        $renderer = new \Diff_Renderer_Html_SideBySide;
        return $diff->Render($renderer);
    }

    /*
     *
     */
    public function format($css)
    {
        $cssGenerator = new Generator\Css($this->logger);
        $formattedCss = $cssGenerator->format($css);

        return $formattedCss;
    }
}
