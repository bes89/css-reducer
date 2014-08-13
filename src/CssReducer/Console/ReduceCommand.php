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
                'Where to save the reduced css?',
                false
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $css = file_get_contents($input->getArgument('file'));

        $logger = new \CssReducer\Log\Monolog();

        $manager = new \CssReducer\Manager($logger);
        $reducedCss = $manager->reduce($css);

        if ($input->getArgument('save_file')) {
            file_put_contents($input->getArgument('save_file'), $reducedCss);
        }

        $s1 = strlen($css);
        $s2 = strlen($reducedCss);
        $s3 = (1 - ($s2 / $s1)) * 100;

        $output->writeln(sprintf('Complete: %s / %s, %.2f%%',
            $s1, $s2, $s3
        ));
    }
}