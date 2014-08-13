<?php

namespace CssReducer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ReduceCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('css:reduce')
            ->setDescription('Reduces css')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'The css file to reduce'
            )
            ->addArgument(
                'save_file',
                InputArgument::OPTIONAL,
                'Where to save the reduced css?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}