<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RootAction extends Controller
{
    public function __invoke()
    {
        return redirect('https://github.com/portal-dots/downloads', 301);
    }
}
