<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

error_reporting(E_ALL);

$loader = require __DIR__.'/../src/bootstrap.php';
$loader->add('CssReducer\Test', __DIR__);