#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use CssReducer\Console\ReduceCommand;

$application = new Application();
$application->add(new ReduceCommand);
$application->run();