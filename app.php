#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use CssReducer\Console\ReduceCommand;
use CssReducer\Console\PropertyCommand;

$application = new Application();
$application->add(new ReduceCommand);
$application->add(new PropertyCommand);
$application->run();
