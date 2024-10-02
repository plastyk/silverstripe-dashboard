<?php

namespace Plastyk\Dashboard\Admin;

use Plastyk\Dashboard\Model\DashboardPanelSection;
use ReflectionClass;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\View\Requirements;

class DashboardAdmin extends LeftAndMain implements PermissionProvider
{
    private static $url_segment = 'dashboard';
    private static $menu_title = 'Dashboard';
    private static $menu_priority = 1000;
    private static $menu_icon_class = 'font-icon-dashboard';

    private static $required_permission_codes = 'CMS_ACCESS_DASHBOARDADMIN';

    public function init()
    {
        parent::init();
        Requirements::css('https://use.fontawesome.com/releases/v6.6.0/css/all.css');
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
        $title = _t('DashboardAdmin.MENUTITLE', LeftAndMain::menu_title('DashboardAdmin'));

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
        return $this->renderWith('Plastyk/Dashboard/Includes/DashboardContent');
    }

    public function getDashboardPanels()
    {
        return $this->renderWith('Plastyk/Dashboard/DashboardPanels');
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

    public function getDashboardPanelSections()
    {
        $dashboardPanelSections = $this->getDashboardPanelSectionObjects();

        $content = '';

        foreach($dashboardPanelSections as $dashboardPanelSection) {
            $content .= $dashboardPanelSection->forTemplate();
        }

        return DBField::create_field('HTMLText', $content);
    }

    /**
     * Return the DashboardPanelSection objects
     *
     * @return DashboardPanelSection[] Array of DashboardPanelSection objects
     */
    private function getDashboardPanelSectionObjects()
    {
        $dashboardPanelSections = ClassInfo::subclassesFor(DashboardPanelSection::class);

        $sections = [];

        if ($dashboardPanelSections && count($dashboardPanelSections ?? []) > 0) {
            foreach ($dashboardPanelSections as $dashboardPanelSection) {
                $reflectionClass = new ReflectionClass($dashboardPanelSection);

                if ($reflectionClass->isAbstract()) {
                    continue;
                }

                $dashboardPanelSectionObject = $dashboardPanelSection::create();

                if (! $dashboardPanelSectionObject->getEnabled()) {
                    continue;
                }

                $sections[$dashboardPanelSection] = $dashboardPanelSectionObject;
            }
        }

        uasort($sections, function ($a, $b) {
            if ($a->getSort() == $b->getSort()) {
                return 0;
            } else {
                return ($a->getSort() < $b->getSort()) ? -1 : 1;
            }
        });

        return $sections;
    }
}
