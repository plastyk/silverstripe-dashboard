<?php

namespace Plastyk\Dashboard\Panels;

use \DateTime;
use Plastyk\Dashboard\Admin\DashboardAdmin;
use Plastyk\Dashboard\Model\DashboardPanel;
use Plastyk\Dashboard\Model\UpdateVersion;
use Plastyk\Dashboard\Model\UpdateVersionList;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Manifest\VersionProvider;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Security\Permission;
use SilverStripe\View\Requirements;

class UpdatePanel extends DashboardPanel
{
    public function canView($member = null)
    {
        if (Permission::checkMember($member, 'CMS_ACCESS_ADMIN')) {
            $currentVersion = $this->getCurrentSilverstripeVersion();
            if (!$currentVersion->PreRelease) {
                $versions = $this->getSilverstripeVersions()->filterNewerVersions($currentVersion);

                return $versions->hasNewerVersion($currentVersion);
            }
        }

        return false;
    }

    public function getData()
    {
        $data = parent::getData();

        $currentVersion = $this->getCurrentSilverstripeVersion();
        $data['CurrentSilverstripeVersion'] = $currentVersion->FullVersion;
        $latestVersion = $this->getLatestSilverstripeVersion();
        $data['LatestSilverstripeVersion'] = $latestVersion->FullVersion;
        $data['UpdateVersionLevel'] = UpdateVersion::getVersionDifference($currentVersion, $latestVersion);

        $data['ContactEmail'] = DashboardAdmin::config()->contact_email ?: false;
        $data['ContactName'] =
            DashboardAdmin::config()->contact_name ?: _t('UpdatePanel.YOURWEBDEVELOPER', 'your web developer');
        $data['ContactContent'] = $this->getContactContent();

        return $data;
    }

    public function init()
    {
        parent::init();
        Requirements::css('plastyk/dashboard:css/dashboard-update-panel.css');
        Requirements::javascript('plastyk/dashboard:javascript/dashboard-update-panel.js');
    }

    public function getContactContent()
    {
        $contactEmail = DashboardAdmin::config()->contact_email ?: false;
        $contactName =
            DashboardAdmin::config()->contact_name ?: _t('UpdatePanel.YOURWEBDEVELOPER', 'your web developer');

        if ($contactEmail) {
            $contactName = '<a href="mailto:' . $contactEmail . '">' . $contactName . '</a>';
        }

        $content = _t(
            'UpdatePanel.IFYOUWOULDLIKETOUPDATE',
            'If you would like to update to the latest version please contact {contactName}.',
            'Update message',
            ['contactName' => $contactName]
        );

        return DBField::create_field('HTMLText', $content);
    }

    public function getCurrentSilverstripeVersion()
    {
        $updatePanelCache = Injector::inst()->get(CacheInterface::class . '.plastykDashboardCache');

        if ($updatePanelCache->has('CurrentSilverstripeVersion')) {
            return UpdateVersion::fromVersionString($updatePanelCache->get('CurrentSilverstripeVersion'));
        }

        $versions = Injector::inst()->get(VersionProvider::class)->getModuleVersionFromComposer([
            'silverstripe/framework',
        ]);

        $currentVersionNumber = '';

        if (isset($versions['silverstripe/framework'])) {
            $currentVersionNumber = $versions['silverstripe/framework'];
        }

        $updatePanelCache->set('CurrentSilverstripeVersion', $currentVersionNumber);

        return UpdateVersion::fromVersionString($currentVersionNumber);
    }

    protected function getSilverstripeVersions()
    {
        $versions = UpdateVersionList::getPackagistVersions()->filter([
            'PreRelease' => false,
        ])->sortByVersion('DESC');

        $ignoreMajorUpdates = UpdatePanel::config()->ignore_major_updates;
        if (isset($ignoreMajorUpdates)) {
            $currentVersion = $this->getCurrentSilverstripeVersion();
            if (is_bool($ignoreMajorUpdates)) {
                if ($ignoreMajorUpdates) {
                    $versions = $versions->filter(['Major' => $currentVersion->Major]);
                }
            } elseif (is_string($ignoreMajorUpdates) && strtotime($ignoreMajorUpdates)) {
                $currentTime = new DateTime(DBDatetime::now()->Value);
                $versions = $versions->filterByCallback(
                    function ($item) use ($currentTime, $currentVersion, $ignoreMajorUpdates) {
                        if ($item->Major > $currentVersion->Major) {
                            $releaseDate = new DateTime($item->ReleaseDate);
                            $waitAfterRelease = $releaseDate->modify($ignoreMajorUpdates);

                            return $currentTime > $waitAfterRelease;
                        }

                        return true;
                    }
                );
            } else {
                user_error('Invalid config value for UpdatePanel::ignore_major_updates', E_USER_WARNING);
            }
        }

        return $versions;
    }

    public function getLatestSilverstripeVersion()
    {
        return $this->getSilverstripeVersions()->first();
    }
}
