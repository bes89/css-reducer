<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class PropertyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('css:property')
            ->setDescription('Reduces css')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelper('dialog');
        $properties = array('Font', 'Background'); // TODO: provide a list all of supported properties

        $name = $dialog->askAndValidate(
            $output,
            "Please enter the name of a property:\t",
            function ($answer) use ($properties) {
                if (!in_array($answer, $properties)) {
                    throw new \RuntimeException(
                        'Allowed properties are: ' . implode(', ', $properties)
                    );
                }

                return $answer;
            },
            false,
            null,
            $properties
        );
    }
}

