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

        $currentVersion = $this->getCurrentSilverStripeVersion();
        $data['CurrentSilverStripeVersion'] = $currentVersion;
        $latestVersion = $this->getLatestSilverStripeVersion();
        $data['LatestSilverStripeVersion'] = $latestVersion;
        $data['UpdateVersionLevel'] = $this->getVersionLevelDifference($currentVersion, $latestVersion);

        $data['ContactEmail'] = DashboardAdmin::config()->contact_email ?: false;
        $data['ContactName'] = DashboardAdmin::config()->contact_name ?: _t('UpdatePanel.YOURWEBDEVELOPER', 'your web developer');
        $data['ContactContent'] = $this->getContactContent();

        return $data;
    }

    public function init()
    {
        parent::init();
        Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-update-panel.css');
        Requirements::javascript(DASHBOARD_ADMIN_DIR . '/javascript/dashboard-update-panel.js');
    }

    public function getContactContent()
    {
        $contactEmail = DashboardAdmin::config()->contact_email ?: false;
        $contactName = DashboardAdmin::config()->contact_name ?: _t('UpdatePanel.YOURWEBDEVELOPER', 'your web developer');

        if ($contactEmail) {
            $contactName = '<a href="mailto:' . $contactEmail . '">' . $contactName . '</a>';
        }

        $content = _t(
            'UpdatePanel.IFYOUWOULDLIKETOUPDATE',
            'If you would like to update to the latest version please contact {contactName}.',
            'Update message',
            array('contactName' => $contactName)
        );

        return DBField::create_field('HTMLText', $content);
    }

    public function getCurrentSilverStripeVersion()
    {
        $updatePanelCache = SS_Cache::factory('DashboardUpdatePanel');
        $result = $updatePanelCache->load('CurrentSilverStripeVersion');
        if ($result) {
            return $result;
        }

        $versions = explode(', ', Injector::inst()->get('LeftAndMain')->CMSVersion());
        if (!empty($versions)) {
            foreach ($versions as $version) {
                if (strpos($version, 'Framework: ') !== false) {
                    $result = substr($version, 11);
                    break;
                }
            }
        }

        $updatePanelCache->save($result, 'CurrentSilverStripeVersion');
        return $result;
    }

    public function getLatestSilverStripeVersion()
    {
        $versions = $this->getSilverStripeVersions();
        if ($versions && count($versions) > 0) {
            return $versions[0];
        }
        return false;
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

    private function getSilverStripeVersions()
    {
        $updatePanelCache = SS_Cache::factory('DashboardUpdatePanel');
        $result = $updatePanelCache->load('SilverStripeVersions');
        if ($result) {
            return json_decode($result);
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

        $result = array();
        foreach ($versionItems as $versionItem) {
            $versionNumber = $versionItem['version'];
            if (isset($versionNumber) && !preg_match('/[^0-9\.]/', $versionNumber)) {
                $result[] = $versionNumber;
            }
        }

        $updatePanelCache->save(json_encode($result), 'SilverStripeVersions');
        return $result;
    }

    public function getVersionLevelDifference($currentVersion, $newVersion)
    {
        $currentVersionParts = explode('.', $currentVersion);
        $newVersionParts = explode('.', $newVersion);

        if ($this->isNewestSilverStripeVersion($currentVersion, $newVersion)) {
            return false;
        }

        if (count($currentVersionParts) === 3 && count($newVersionParts) === 3) {
            if ($newVersionParts[0] > $currentVersionParts[0]) {
                return 'major';
            }
            if ($newVersionParts[1] > $currentVersionParts[1]) {
                return 'minor';
            }
            if ($newVersionParts[2] > $currentVersionParts[2]) {
                return 'security';
            }
        }
        return false;
    }
}
