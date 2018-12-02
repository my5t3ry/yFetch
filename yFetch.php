#!/usr/bin/php
<?php
set_time_limit(0); //in seconds
require __DIR__ . '/vendor/autoload.php';
require __DiR__ . "/ClassLoader.php";

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use splitbrain\phpcli\Options;

class Minimal extends \splitbrain\phpcli\PSR3CLI
{
    private $cfgFile = "config.json";
    private $cfgService;
    private $itemService;
    private $metaDataService;
    private $fetchService;
    private $channelService;
    private $metrixService;
    private $log;
    private $container;

    protected function setup(Options $options)
    {
        $builder = new DI\ContainerBuilder();
        $builder->addDefinitions(getDIContainerCfg());
        $this->container = $builder->build();
        $options->setHelp('yFetch 0.4');
        $options->registerOption('version', 'print version', 'v');
        $options->registerOption('cfg', 'config file', 'c');
        $options->registerOption('scrape', 'mode', 's');
        $options->registerOption('fetch', 'mode', 'f');
    }

    protected function main(Options $options)
    {
        echo file_get_contents(__DIR__ . "/banner.txt") . PHP_EOL . PHP_EOL . PHP_EOL;
        $this->log = new Logger("log");
        $this->log->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
        sleep(2);
        $this->log->info("--> here we go...");
        if ($options->getOpt('cfg')) {
            $this->cfgFile = $options->getOpt('cfg');
        } else if ($options->getOpt('help')) {
            echo $options->help();
        }
        $this->cfgService = $this->container->get('CfgService');
        $this->cfgService->loadCfgFile($this->cfgFile);
        if ($this->cfgService->getValue("force-override")) {
            $this->cleanTarget();
        }
        $this->metaDataService = $this->container->get('MetaDataService');
        if ($options->getOpt('scrape')) {
            $this->log->info("--> started in scrape only mode");
            $this->scrape();
        } elseif ($options->getOpt('fetch')) {
            $this->log->info("--> started in fetch only mode");
            $this->downloadItems();
        } else {
            $this->log->info("--> started in fetch/scrape mode");
            $this->downloadItems();
            $this->scrape();
        }
        $this->log->info("--> stop.");
    }


    private function cleanTarget()
    {
        $path = $this->getOutputDir();
        $this->delete_directory($path);
        mkdir($path);
        $this->log->info("--> cleaned output directory");
    }


    public function getOutputDir(): string
    {
        return __DIR__ . "/../output/";
    }


    private function delete_directory($dirname)
    {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file))
                    unlink($dirname . "/" . $file);
                else
                    $this->delete_directory($dirname . '/' . $file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }

    protected function scrape()
    {
        $this->channelService = $this->container->get('ChannelService');
        $this->channelService->scrape();
        $this->metaDataService->updateChannelService();
        $this->metrixService = $this->container->get('MetrixService');
        $this->metrixService->build();
    }

    protected function downloadItems()
    {
        $this->itemService = $this->container->get('FetchItemService');
        $this->itemService->parseInput();
        $this->metaDataService->updateFetchService();
        $this->fetchService = $this->container->get('FetchService');
    }
}

$cli = new Minimal();
$cli->run();