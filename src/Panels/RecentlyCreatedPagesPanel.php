<?php

namespace Plastyk\Dashboard\Panels;

use Plastyk\Dashboard\Model\DashboardPanel;
use SilverStripe\CMS\Controllers\CMSPagesController;
use SilverStripe\Security\Permission;

class RecentlyCreatedPagesPanel extends DashboardPanel
{
    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain')
            && class_exists(CMSPagesController::class)
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
            'Created:GreaterThan' => date('c', strtotime('-6 months')),
        ])->sort('Created DESC')->limit(8);
    }
}
