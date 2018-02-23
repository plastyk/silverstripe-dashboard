<?php

namespace Plastyk\Dashboard\Panels;

use Plastyk\Dashboard\Model\DashboardPanel;
use SilverStripe\Security\Permission;
use SilverStripe\View\Requirements;

class RecentlyCreatedPagesPanel extends DashboardPanel
{
    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain') && class_exists('SilverStripe\CMS\Controllers\CMSPagesController') && parent::canView($member);
    }

    public function getData()
    {
        $data = parent::getData();

        $data['Results'] = $this->Results();

        return $data;
    }

    public function Results()
    {
        return \Page::get()->filter('Created:GreaterThan', date('c', strtotime('-6 months')))->sort('Created DESC')->limit(8);
    }
}
