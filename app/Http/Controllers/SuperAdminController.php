<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class SuperAdminController extends Controller
{
    public function migrate()
    {
        if (!defined('STDIN'))  define('STDIN',  fopen('php://stdin',  'rb'));
        if (!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'wb'));
        if (!defined('STDERR')) define('STDERR', fopen('php://stderr', 'wb'));

        return Artisan::call('migrate');
    }
}
