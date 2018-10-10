<?php

namespace Plastyk\Dashboard\Panels;

use Plastyk\Dashboard\Model\DashboardPanel;
use SilverStripe\Security\Permission;
use SilverStripe\View\Requirements;

class RecentlyEditedPagesPanel extends DashboardPanel
{
    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain')
            && class_exists('SilverStripe\CMS\Controllers\CMSPagesController')
            && parent::canView($member);
    }

    public function getData()
    {
        $data = parent::getData();

        $data['Results'] = $this->getResults();

        return $data;
    }

    public function getResults()
    {
        return \Page::get()->filter([
            'LastEdited:GreaterThan' => date('c', strtotime('-6 months'))
        ])->sort('LastEdited DESC')->limit(8);
    }
}
