<?php

namespace Plastyk\Dashboard\Search;

use Plastyk\Dashboard\Model\DashboardSearchResultPanel;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Member;

class DashboardSearchResultMemberPanel extends DashboardSearchResultPanel
{
    protected $className = Member::class;
    protected $searchFields = array('FirstName', 'Surname', 'Email');
    protected $sort = array('FirstName' => 'ASC', 'Surname' => 'ASC', 'Email' => 'ASC');

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_SecurityAdmin') && class_exists('SilverStripe\Admin\SecurityAdmin') && parent::canView($member);
    }
}
