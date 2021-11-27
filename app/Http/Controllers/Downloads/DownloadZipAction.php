<?php

namespace App\Http\Controllers\Downloads;

use App\Http\Controllers\Controller;

class DownloadZipAction extends Controller
{
    public function __invoke(string $version)
    {
        return redirect("https://github.com/portal-dots/PortalDots/releases/download/v{$version}/PortalDots.zip");
    }
}
