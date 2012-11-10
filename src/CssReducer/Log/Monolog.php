<?php

/*
 * This file is part of the css-reducer
 *
 * (c) Besnik Brahimi <besnik.br@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CssReducer\Log;

use Monolog\Logger;
use Monolog\Handler\TestHandler;
use Monolog\Formatter\LineFormatter;


class Monolog extends Logger implements LoggerInterface
{
    /**
     * @var \Monolog\Logger|null
     */
    protected $logger = null;

    /**
     *
     */
    public function __construct()
    {
        $handler = new TestHandler();

        $format = "[%datetime%] %level_name%: %message%\n";
        $dateFormat = 'H:i:s';

        $handler->setFormatter(new LineFormatter($format, $dateFormat));

        $this->logger = new Logger('');
        $this->logger->pushHandler($handler);

    }

    /**
     * @param $message
     * @param string $type
     * @throws \InvalidArgumentException
     */
    public function add($message, $type = 'info')
    {
        switch($type)
        {
            case 'info' : $this->logger->addInfo($message); break;
            case 'warning' : $this->logger->addWarning($message); break;
            case 'error' : $this->logger->addError($message); break;
            default: throw new \InvalidArgumentException(sprintf('Invalid log type "%s"', $type));
        }
    }

    /**
     * @return string
     */
    public function getContents()
    {
        $contents = "";

        /* @var $testHadnler TestHandler */
        $testHandler = $this->logger->handlers[0];

        if ($testHandler instanceof TestHandler)
        {
            foreach ($testHandler->getRecords() as $record)
            {
                $contents .= $record['formatted'];
            }
        }

        return $contents;
    }
}
