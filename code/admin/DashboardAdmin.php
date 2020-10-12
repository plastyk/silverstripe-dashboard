<?php

class DashboardAdmin extends LeftAndMain implements PermissionProvider
{
    private static $url_segment = 'dashboard';
    private static $menu_title = 'Dashboard';
    private static $menu_priority = 1000;

    public function init()
    {
        parent::init();
        Requirements::css('https://use.fontawesome.com/releases/v5.15.1/css/all.css');
        Requirements::css(DASHBOARD_ADMIN_DIR . '/css/grid.css');
        Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard.css');
        Requirements::javascript(DASHBOARD_ADMIN_DIR . '/javascript/dashboard.js');
    }

    public function providePermissions()
    {
        $title = _t('DashboardAdmin.MENUTITLE', LeftAndMain::menu_title_for_class('DashboardAdmin'));
        return array(
            'CMS_ACCESS_DASHBOARDADMIN' => array(
                'name' => _t('CMSMain.ACCESS', "Access to '{title}' section", 'Permissions Label', array('title' => $title)),
                'category' => $title,
                'help' => 'Allow use of the CMS Dashboard'
            )
        );
    }

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_DASHBOARDADMIN');
    }

    public function DashboardContent()
    {
        return $this->renderWith('DashboardContent');
    }

    public function DashboardPanels()
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

    public function getPanelAccentColor()
    {
        return DashboardAdmin::config()->panel_accent_color;
    }
}
