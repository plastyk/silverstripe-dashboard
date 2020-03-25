<?php

namespace Plastyk\Dashboard\Search;

use Plastyk\Dashboard\Model\DashboardSearchResultPanel;
use SilverStripe\Admin\SecurityAdmin;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

class DashboardSearchResultMemberPanel extends DashboardSearchResultPanel
{
    protected $className = Member::class;
    protected $searchFields = ['FirstName', 'Surname', 'Email'];
    protected $sort = ['FirstName' => 'ASC', 'Surname' => 'ASC', 'Email' => 'ASC'];

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_SecurityAdmin')
            && class_exists(SecurityAdmin::class)
            && parent::canView($member);
    }
}
