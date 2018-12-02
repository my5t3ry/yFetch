<?php
/**
 * User: sascha.bast
 * Date: 12/1/18
 * Time: 10:49 PM
 */

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

function getDIContainerCfg()
{
    unlink("yFetch.log");
    return [
        Psr\Log\LoggerInterface::class => DI\factory(function () {
            $logger = new \Monolog\Logger("log");
            $fileHandler = new StreamHandler('yFetch.log', Logger::DEBUG);
            $fileHandler->setFormatter(new LineFormatter());
            $logger->pushHandler($fileHandler);
            $logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO)); 
            return $logger;
        })];
}