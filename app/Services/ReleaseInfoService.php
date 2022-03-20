<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Contracts\Cache\Repository as Cache;
use App\Services\ValueObjects\Version;
use App\Services\ValueObjects\Release;
use Carbon\CarbonImmutable;

class ReleaseInfoService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @param Client $client
     * @param Cache  $cache
     */
    public function __construct(Client $client, Cache $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * 指定されたメジャーバージョン内の全リリース情報を取得する
     *
     * @return Release[]
     */
    public function getAllReleases(Int $major_version, Bool $includes_prerelease = false): array
    {
        return $this->cache->remember(
            'getAllReleases/' . $major_version . ($includes_prerelease ? '-prelease' : ''),
            120,
            function () use ($major_version, $includes_prerelease) {
                $result = [];

                // APIから1ページ分取得
                for ($page = 1; $page <= 1; $page++) {
                    $path = sprintf(
                        'https://api.github.com/repos/portal-dots/PortalDots/releases?per_page=%d&page=%d',
                        100,
                        $page
                    );
                    try {
                        $releases = json_decode((string) $this->client->get($path)->getBody());
                    } catch (\Exception $e) {
                        return [];
                    }

                    foreach ($releases as $release) {
                        $version_info = Version::parse($release->tag_name);

                        if (empty($version_info)) {
                            continue;
                        }

                        if ($version_info->getMajor() === $major_version) {
                            if ($release->prerelease && !$includes_prerelease) {
                                continue;
                            }

                            $result[] = new Release(
                                $version_info,
                                new CarbonImmutable($release->published_at),
                                $release->html_url,
                                $release->assets[0]->browser_download_url,
                                $release->assets[0]->size,
                                $release->body
                            );
                        }
                    }
                }

                // バージョン順にソートする
                usort($result, function (Release $a, Release $b) {
                    if (($compare_result = version_compare($b->getVersion()->getFullVersion(), $a->getVersion()->getFullVersion())) === 0) {
                        // プレリリースバージョンで比較する
                        return $b->getVersion()->getPrerelease() <=> $a->getVersion()->getPrerelease();
                    }
                    return $compare_result;
                });

                return $result;
            }
        );
    }
}
