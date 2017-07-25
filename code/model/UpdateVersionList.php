<?php

class UpdateVersionList extends ArrayList
{
    public function __construct(array $items = array())
    {
        for ($i = 0, $l = count($items); $i < $l; $i++) {
            if (is_string($items[$i])) {
                $items[$i] = UpdateVersion::from_version_string($items[$i]);
            }
        }

        parent::__construct($items);
    }

    public function filterMajorReleases($includePreRelease = false)
    {
        $args = array(
            'Minor' => 0,
            'Patch' => 0
        );
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
            $version = UpdateVersion::from_version_string($version);
        }
        $result = $this->filterByCallback(function ($item) use ($version) {
            return $item->VersionCode > $version->VersionCode;
        });
        return $result;
    }

    public function sortByVersion($direction = 'ASC')
    {
        return $this->sort(array(
            'VersionCode' => $direction,
            'ReleaseDate' => $direction
        ));
    }

    public static function get_packagist_versions()
    {
        $versionListCache = SS_Cache::factory('VersionList');
        $result = $versionListCache->load('PackagistVersions');
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
            return false;
        }

        $versionJSON = json_decode($versionFeed, true);
        if (!$versionJSON || !isset($versionJSON['package']['versions'])) {
            return false;
        }

        $versionItems = $versionJSON['package']['versions'];

        $result = array();
        foreach ($versionItems as $versionItem) {
            $version = UpdateVersion::from_version_string($versionItem['version']);
            $version->ReleaseDate = $versionItem['time'];
            $result[] = $version;
        }

        $versionListCache->save(json_encode($result), 'PackagistVersions');
        return UpdateVersionList::create($result);
    }
}
