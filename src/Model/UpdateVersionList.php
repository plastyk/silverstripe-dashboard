<?php

namespace Plastyk\Dashboard\Model;

use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ArrayList;

class UpdateVersionList extends ArrayList
{
    public function __construct(array $items = [])
    {
        for ($i = 0, $l = count($items); $i < $l; $i++) {
            if (is_string($items[$i])) {
                $items[$i] = UpdateVersion::fromVersionString($items[$i]);
            }
        }

        parent::__construct($items);
    }

    public function filterMajorReleases($includePreRelease = false)
    {
        $args = [
            'Minor' => 0,
            'Patch' => 0,
        ];
        if (!$includePreRelease) {
            $args['PreRelease'] = false;
        }

        return $this->filter($args);
    }

    public function hasNewerVersion($version)
    {
        $result = $this->filterNewerVersions($version);

        return $result->count() > 0;
    }

    public function filterNewerVersions($version)
    {
        if (is_string($version)) {
            $version = UpdateVersion::fromVersionString($version);
        }
        $result = $this->filterByCallback(function ($item) use ($version) {
            return $item->VersionCode > $version->VersionCode;
        });

        return $result;
    }

    public function sortByVersion($direction = 'ASC')
    {
        return $this->sort([
            'VersionCode' => $direction,
            'ReleaseDate' => $direction,
        ]);
    }

    public static function getPackagistVersions()
    {
        $versionListCache = Injector::inst()->get(CacheInterface::class . '.plastykDashboardCache');
        $result = $versionListCache->get('PackagistVersions');
        if ($result) {
            $result = json_decode($result);

            return UpdateVersionList::create($result);
        }

        $versionRequest = curl_init();
        curl_setopt($versionRequest, CURLOPT_URL, 'https://packagist.org/packages/silverstripe/framework.json');
        curl_setopt($versionRequest, CURLOPT_RETURNTRANSFER, 1);
        $versionFeed = curl_exec($versionRequest);
        curl_close($versionRequest);

        if (!$versionFeed) {
            return UpdateVersionList::create();
        }

        $versionJSON = json_decode($versionFeed, true);
        if (!$versionJSON || !isset($versionJSON['package']['versions'])) {
            return UpdateVersionList::create();
        }

        $versionItems = $versionJSON['package']['versions'];

        $result = [];
        foreach ($versionItems as $versionItem) {
            $version = UpdateVersion::fromVersionString($versionItem['version']);
            $version->ReleaseDate = $versionItem['time'];
            $result[] = $version;
        }

        $versionListCache->set('PackagistVersions', json_encode($result));

        return UpdateVersionList::create($result);
    }
}
