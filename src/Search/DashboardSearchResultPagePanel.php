<?php

namespace Plastyk\Dashboard\Search;

use Plastyk\Dashboard\Model\DashboardSearchResultPanel;
use SilverStripe\Security\Permission;

class DashboardSearchResultPagePanel extends DashboardSearchResultPanel
{
    protected $className = 'Page';
    protected $searchFields = array('Title', 'Content');
    protected $sort = array('Title' => 'ASC');

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain') && class_exists('SilverStripe\CMS\Controllers\CMSPagesController') && parent::canView($member);
    }
}
