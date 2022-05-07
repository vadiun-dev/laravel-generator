<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

class RouteWriter
{
    public static function writeFile($routeMethod, $routeName, $actionNamespace): void
    {
        $r = "Route::$routeMethod('$routeName', [\\$actionNamespace::class, 'asController']);\r\n\t//route";
        $file = base_path('routes/api.php');
        file_put_contents($file, str_replace('//route', $r, file_get_contents($file)));
    }
}
