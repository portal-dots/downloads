<?php

namespace App\Http\Controllers\Releases;

use App\Http\Controllers\Controller;
use App\Services\ReleaseInfoService;
use Illuminate\Http\Request;

class IndexAction extends Controller
{
    /**
     * @var ReleaseInfoService
     */
    private $releaseInfoService;

    public function __construct(ReleaseInfoService $releaseInfoService)
    {
        $this->releaseInfoService = $releaseInfoService;
    }

    public function __invoke(Request $request)
    {
        $major_version = filter_var($request->input('current_major_version'), FILTER_VALIDATE_INT);

        if (empty($major_version)) {
            abort(404);
        }

        $releases = $this->releaseInfoService->getAllReleases($major_version);

        return view('releases.index', [
            'major_version' => $major_version,
            'releases' => $releases,
        ]);
    }
}
