<?php

namespace Hitocean\Generator\Commands\Generators;

use Illuminate\Filesystem\Filesystem;

class FileAdmin
{
    public static function getStub($stub, $path = null)
    {
        $files = new Filesystem();
        if($path)
            return $files->get($path);
        else
            return $files->get(__DIR__ . '/Backend/Stubs/' .$stub.'.stub');
    }

    public static function populateStub($stub, $fakes)
    {
        foreach($fakes as $fake => $value)
            $stub = str_replace($fake, $value, $stub);

        return $stub;
    }

    public static function writeFile($stubFileName, $path, $fakes, $stubFilePath = null)
    {
        if(file_exists($path))
            return;
        $stub = self::getStub($stubFileName, $stubFilePath);
        $files = new Filesystem();
        $files->put($path, self::populateStub($stub, $fakes));
    }

    public static function overrideFile($path, $dataToOverride)
    {
        $string = self::getStub(1, $path);
        $files = new Filesystem();
        $files->put($path, self::populateStub($string, $dataToOverride));
    }


}
