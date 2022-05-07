<?php


namespace Hitocean\Generator\Commands\Generators\Config;


class ConfigFinder
{
    public static function getModels(): array
    {
        $configs =[];
        $iterator = new \DirectoryIterator(base_path('generators/models'));
        foreach ($iterator as $json_conf)
        {
            if (!$json_conf->isDot()) {
                $configData = json_decode(file_get_contents($json_conf->getPath().'/'.$json_conf->getFilename()));
                $configs[] = ConfigFactory::makeModel($configData);

            }
        }
        return $configs;
    }

    public static function getActions(): array
    {
        $configs =[];
        $iterator = new \DirectoryIterator(base_path('generators/actions'));
        foreach ($iterator as $json_conf)
        {
            if (!$json_conf->isDot()) {
                $configData = json_decode(file_get_contents($json_conf->getPath().'/'.$json_conf->getFilename()));
                $configs[] = ConfigFactory::makeAction($configData);

            }
        }
        return $configs;
    }

    public static function getFrontendAbms(): array
    {
        $configs =[];
        $iterator = new \DirectoryIterator(__DIR__.'/../Frontend/Abms');
        foreach ($iterator as $json_conf)
        {
            if (!$json_conf->isDot()) {
                $configData = json_decode(file_get_contents($json_conf->getPath().'/'.$json_conf->getFilename()));
                $configs[] = ConfigFactory::makeFrontendAbm($configData);

            }
        }
        return $configs;
    }
}
