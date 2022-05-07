<?php

namespace Hitocean\Generator\Commands\Generators\Backend\Writers;

use Illuminate\Support\Str;

class GateWriter
{
    public static function writeFile($action_name): void
    {
        $action_name = Str::snake($action_name, '-');
        $r = "\Gate::define('$action_name'".', fn (User $user) => $user->hasAnyRole([Roles::SUPER_ADMIN]));'."\r\n\t//gate";
        $file = base_path('app/Providers/AuthServiceProvider.php');
        file_put_contents($file, str_replace('//gate', $r, file_get_contents($file)));
    }

    public static function gateName($action_name): string
    {
        return  Str::snake($action_name, '-');
    }
}
