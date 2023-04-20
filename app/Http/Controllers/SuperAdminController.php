<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class SuperAdminController extends Controller
{
    public function migrate()
    {
        return Artisan::call('migrate');
    }
}
