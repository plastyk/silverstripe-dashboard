<?php

namespace Plastyk\Dashboard\QuickLinks;

use Plastyk\Dashboard\Model\QuickLink;
use SilverStripe\Admin\SecurityAdmin;
use SilverStripe\Security\Permission;

class Users extends QuickLink
{
    private static $title = 'Users';
    private static $url = '{$AdminURL}security/';
    private static $icon = 'fa-users';
    private static $sort = 60;

    public function canView($member = null): bool
    {
        return Permission::checkMember($member, 'CMS_ACCESS_SecurityAdmin') &&
            class_exists(SecurityAdmin::class);
    }
}
