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
    public function getAllReleases(Int $major_version): array
    {
        return $this->cache->remember(
            'getAllReleases/' . $major_version,
            120,
            function () use ($major_version) {
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
                        $version_info = $this->version($release->tag_name);

                        if (empty($version_info)) {
                            continue;
                        }

                        if ($version_info->getMajor() === $major_version && !$release->prerelease) {
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
                    return version_compare($b->getVersion()->getFullVersion(), $a->getVersion()->getFullVersion());
                });

                return $result;
            }
        );
    }

    /**
     * バージョン文字列からバージョン情報配列を取得
     *
     * @return Version|null
     */
    public function version(string $version_string): ?Version
    {
        preg_match('/(\d+)\.(\d+)\.(\d+)/', $version_string, $matches);
        if (!isset($matches[1]) || !isset($matches[2]) || !isset($matches[3])) {
            return null;
        }
        return new Version((int)$matches[1], (int)$matches[2], (int)$matches[3]);
    }
}
