<?php

class UpdatePanel extends DashboardPanel
{
    public function canView($member = null)
    {
        if (Permission::check('CMS_ACCESS_ADMIN')) {
            $currentVersion = $this->getCurrentSilverStripeVersion();
            $latestVersion = $this->getLatestSilverStripeVersion();

            return !$this->isNewestSilverStripeVersion($currentVersion, $latestVersion);
        }
        return false;
    }

    public function getData()
    {
        $data = parent::getData();

        $data['CurrentSilverStripeVersion'] = $this->getCurrentSilverStripeVersion();
        $data['LatestSilverStripeVersion'] = $this->getLatestSilverStripeVersion();
        $data['UpdateVersionLevel'] = $this->getUpdateVersionLevel();
        $data['DashboardContactEmail'] = DashboardAdmin::config()->contact_email ?: false;
        $data['DashboardContactName'] = DashboardAdmin::config()->contact_name ?: false;

        return $data;
    }

    public function init()
    {
        parent::init();
        Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-update-panel.css');
        Requirements::javascript(DASHBOARD_ADMIN_DIR . '/javascript/dashboard-update-panel.js');
    }

    public function getCurrentSilverStripeVersion()
    {
        $currentVersion = false;
        if (!Session::get('silverstripe_current_version')) {
            $versions = explode(', ', Injector::inst()->get('LeftAndMain')->CMSVersion());
            if (!empty($versions)) {
                foreach ($versions as $version) {
                    if (strpos($version, 'Framework: ') !== false) {
                        $currentVersion = substr($version, 11);
                        Session::set('silverstripe_current_version', $currentVersion);
                    }
                }
            }
        } else {
            $currentVersion = Session::get('silverstripe_current_version');
        }
        return $currentVersion;
    }

    public function getLatestSilverStripeVersion()
    {
        $latestVersion = Session::get('silverstripe_latest_version');
        if ($latestVersion) {
            return $latestVersion;
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
        rsort($versionItems);

        foreach ($versionItems as $versionItem) {
            $versionNumber = $versionItem['version'];
            if (isset($versionNumber) && !preg_match('/[^0-9\.]/', $versionNumber)) {
                if ($this->isNewestSilverStripeVersion($versionNumber, $latestVersion)) {
                    $latestVersion = $versionNumber;
                }
            }
        }

        if ($latestVersion) {
            Session::set('silverstripe_latest_version', $latestVersion);
        }

        return $latestVersion;
    }

    public function isNewestSilverStripeVersion($versionNumber, $latestVersionNumber)
    {
        if ($versionNumber == $latestVersionNumber) {
            return true;
        }
        $versionNumberParts = explode('.', $versionNumber);
        $latestVersionNumberParts = explode('.', $latestVersionNumber);

        for ($versionNumberIndex = 0; $versionNumberIndex < count($versionNumberParts) && $versionNumberIndex < count($latestVersionNumberParts); $versionNumberIndex++) {
            if ($versionNumberParts[$versionNumberIndex] > $latestVersionNumberParts[$versionNumberIndex]) {
                return true;
            } elseif ($versionNumberParts[$versionNumberIndex] < $latestVersionNumberParts[$versionNumberIndex]) {
                break;
            }
        }
        return false;
    }

    public function getUpdateVersionLevel()
    {
        $currentVersion = $this->getCurrentSilverStripeVersion();
        $latestVersion = $this->getLatestSilverStripeVersion();

        if ($this->isNewestSilverStripeVersion($currentVersion, $latestVersion)) {
            return false;
        }

        $currentVersionParts = explode('.', $currentVersion);
        $latestVersionParts = explode('.', $latestVersion);

        if (count($currentVersionParts) === 3 && count($latestVersionParts) === 3) {
            if ($latestVersionParts[0] > $currentVersionParts[0]) {
                return 'major';
            }
            if ($latestVersionParts[1] > $currentVersionParts[1]) {
                return 'minor';
            }
            if ($latestVersionParts[2] > $currentVersionParts[2]) {
                return 'security';
            }
        }
        return false;
    }
}
