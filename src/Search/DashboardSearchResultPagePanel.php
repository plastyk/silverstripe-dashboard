<?php

namespace Plastyk\Dashboard\Search;

use Plastyk\Dashboard\Model\DashboardSearchResultPanel;
use SilverStripe\CMS\Controllers\CMSPagesController;
use SilverStripe\Security\Permission;

class DashboardSearchResultPagePanel extends DashboardSearchResultPanel
{
    protected $className = \Page::class;
    protected $searchFields = ['Title', 'Content'];
    protected $sort = ['Title' => 'ASC'];

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain')
            && class_exists(CMSPagesController::class)
            && parent::canView($member);
    }
}
