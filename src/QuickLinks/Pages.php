<?php

namespace Plastyk\Dashboard\QuickLinks;

use Plastyk\Dashboard\Model\QuickLink;
use SilverStripe\CMS\Controllers\CMSPagesController;
use SilverStripe\Security\Permission;

class Pages extends QuickLink
{
    private static $title = 'Pages';
    private static $url = '{$AdminURL}pages/';
    private static $icon = 'fa-sitemap';
    private static $sort = 50;

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain') &&
            class_exists(CMSPagesController::class);
    }
}
