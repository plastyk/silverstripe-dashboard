<?php

namespace Plastyk\Dashboard\Panels;

use Plastyk\Dashboard\Model\DashboardPanel;
use SilverStripe\Admin\SecurityAdmin;
use SilverStripe\CMS\Controllers\CMSPagesController;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\SiteConfig\SiteConfigLeftAndMain;
use SilverStripe\View\Requirements;

class QuickLinksPanel extends DashboardPanel
{
    public function canView($member = null)
    {
        $data = $this->getData();
        if (!$data['CanView']) {
            return false;
        }

        return parent::canView($member);
    }

    public function init()
    {
        parent::init();
        Requirements::css('plastyk/dashboard:css/dashboard-quick-links-panel.css');
    }

    public function getData()
    {
        $member = Security::getCurrentUser();

        $data = parent::getData();

        $data['CanView'] = false;

        $data['CanViewPages'] = Permission::checkMember($member, 'CMS_ACCESS_CMSMain')
            && class_exists(CMSPagesController::class);
        $data['CanView'] = $data['CanView'] || $data['CanViewPages'];
        $data['CanViewUsers'] = Permission::checkMember($member, 'CMS_ACCESS_SecurityAdmin')
            && class_exists(SecurityAdmin::class);
        $data['CanView'] = $data['CanView'] || $data['CanViewUsers'];
        $data['CanViewSettings'] = Permission::checkMember($member, 'EDIT_SITECONFIG')
            && class_exists(SiteConfigLeftAndMain::class);
        $data['CanView'] = $data['CanView'] || $data['CanViewSettings'];

        $this->extend('updateData', $data);

        return $data;
    }
}
