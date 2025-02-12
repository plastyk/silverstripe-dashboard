<?php

namespace Plastyk\Dashboard\QuickLinks;

use Plastyk\Dashboard\Model\QuickLink;
use SilverStripe\Security\Permission;
use SilverStripe\SiteConfig\SiteConfigLeftAndMain;

class Settings extends QuickLink
{
    private static $title = 'Settings';
    private static $url = '{$AdminURL}/settings/';
    private static $icon = 'fa-cogs';
    private static $sort = 70;

    public function canView($member = null): bool
    {
        return Permission::checkMember($member, 'EDIT_SITECONFIG') &&
            class_exists(SiteConfigLeftAndMain::class);
    }
}
