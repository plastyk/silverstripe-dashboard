<?php

class UpdateVersion extends Object
{
    public $FullVersion = null;
    public $VersionCode = 16777215;
    public $Major = 255;
    public $Minor = 255;
    public $Patch = 255;
    public $Build = null;
    public $PreRelease = true;
    public $PreReleaseType = null;
    public $ReleaseDate = null;

    public static function get_version_code($major, $minor, $patch)
    {
        return ($major << 16) + ($minor << 8) + $patch;
    }

    public static function get_version_difference($currentVersion, $newVersion)
    {
        if (is_string($currentVersion)) {
            $currentVersion = UpdateVersion::from_version_string($currentVersion);
        }
        if (is_string($newVersion)) {
            $newVersion = UpdateVersion::from_version_string($newVersion);
        }

        if ($currentVersion->VersionCode >= $newVersion->VersionCode) {
            return false;
        }

        if ($newVersion->Major > $currentVersion->Major) {
            return 'major';
        } elseif ($newVersion->Minor > $currentVersion->Minor) {
            return 'minor';
        } elseif ($newVersion->Patch > $currentVersion->Patch) {
            return 'patch';
        }

        return false;
    }

    public static function from_version_string($versionString)
    {
        $result = UpdateVersion::create();
        $result->FullVersion = $versionString;
        $result->PreReleaseType = $versionString;

        if (strpos($versionString, 'dev') !== 0) {
            //Semver #10: Build metadata denoted after a plus sign
            $build = explode('+', $versionString, 2);
            if (count($build) > 1) {
                $result->Build = $build[1];
            }

            //Semver #9: Pre-release denoted after a hyphen
            $release = explode('-', $build[0], 2);
            if (count($release) > 1) {
                $result->PreRelease = true;
                $result->PreReleaseType = $release[1];
            } else {
                $result->PreRelease = false;
                $result->PreReleaseType = null;
            }

            //Semver #2: Normal version number in the form X.Y.Z (Major.Minor.Patch)
            $versionNumbers = explode('.', $release[0], 3);
            for ($i = 0, $l = 3; $i < $l; $i++) {
                if ($i < count($versionNumbers) && is_numeric($versionNumbers[$i])) {
                    $versionNumbers[$i] = intval($versionNumbers[$i]);
                } else {
                    $versionNumbers[$i] = 255;
                }
            }
            $result->Major = $versionNumbers[0];
            $result->Minor = $versionNumbers[1];
            $result->Patch = $versionNumbers[2];

            //Semver #4: Major version 0 is not considered stable
            if ($result->Major === 0) {
                $result->PreRelease = true;
            }

            $result->VersionCode = UpdateVersion::get_version_code($result->Major, $result->Minor, $result->Patch);
        }

        return $result;
    }
}
