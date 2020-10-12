<?php

namespace Plastyk\Dashboard\Admin;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\View\Requirements;

class DashboardAdmin extends LeftAndMain implements PermissionProvider
{
    private static $url_segment = 'dashboard';
    private static $menu_title = 'Dashboard';
    private static $menu_priority = 1000;
    private static $menu_icon = 'plastyk/dashboard:images/treeicons/dashboard.png';

    private static $required_permission_codes = 'CMS_ACCESS_DASHBOARDADMIN';

    public function init()
    {
        parent::init();
        Requirements::css('https://use.fontawesome.com/releases/v5.15.1/css/all.css');
        Requirements::css('plastyk/dashboard:css/dashboard.css');
        Requirements::javascript('plastyk/dashboard:javascript/dashboard.js');

        if ($panelAccentColor = DashboardAdmin::config()->panel_accent_color) {
            Requirements::customCSS(<<<CSS
.cms-content.DashboardAdmin .dashboard-panel {
    border-top-color: $panelAccentColor;
}
CSS
            );
        }

        $this->extend('updateInit');
    }

    public function providePermissions()
    {
        $title = _t('DashboardAdmin.MENUTITLE', LeftAndMain::menu_title_for_class('DashboardAdmin'));

        return [
            'CMS_ACCESS_DASHBOARDADMIN' => [
                'name' => _t('CMSMain.ACCESS', "Access to '{title}' section", 'Permissions Label', ['title' => $title]),
                'category' => $title,
                'help' => 'Allow use of the CMS Dashboard',
            ],
        ];
    }

    public function getDashboardContent()
    {
        return $this->renderWith('Includes/DashboardContent');
    }

    public function getDashboardPanels()
    {
        return $this->renderWith('DashboardPanels');
    }

    public function canViewPanel($panelName)
    {
        if (class_exists($panelName)) {
            $panel = new $panelName($this);

            return $panel->canView();
        }

        return false;
    }

    public function showPanel($panelName)
    {
        if (!class_exists($panelName)) {
            return false;
        }

        $panel = new $panelName($this);
        if ($panel->canView()) {
            return $panel->forTemplate();
        }

        return false;
    }
}
