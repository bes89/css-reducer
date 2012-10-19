<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$autoloadFiles = array(
    __DIR__.'/../vendor/autoload.php',
    __DIR__."/../../../autoload.php",
);

if (file_exists($autoloadFiles[0]))
{
    $loader = require $autoloadFiles[0];
}
elseif (file_exists($autoloadFiles[1]))
{
    $loader = require $autoloadFiles[1];
}
else
{
    die("You have to install the project dependencies by running the following commands:".
        "curl -s http://getcomposer.org/installer | php && php composer.phar install");
}

return $loader;