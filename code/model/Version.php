<?php

class Version extends Object
{
    public $FullVersion;
    public $VersionCode;
    public $Major;
    public $Minor;
    public $Patch;
    public $Build;
    public $PreRelease;
    public $PreReleaseType;
    public $ReleaseDate;

    public static function get_version_difference($currentVersion, $newVersion)
    {
        if (is_string($currentVersion)) {
            $currentVersion = Version::from_version_string($currentVersion);
        }
        if (is_string($newVersion)) {
            $newVersion = Version::from_version_string($newVersion);
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
        } else {
            return false;
        }
    }

    public static function from_version_string($versionString)
    {
        $result = Version::create();
        $result->FullVersion = $versionString;
        $result->VersionCode = 0;
        $result->Major = 255;
        $result->Minor = 255;
        $result->Patch = 255;
        $result->Build = null;
        $result->PreRelease = true;
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
        }

        //Create a unique version code using the various version numbers
        $result->VersionCode = ($result->Major << 16) + ($result->Minor << 8) + $result->Patch;

        return $result;
    }
}
