<?php

class DashboardAdmin extends LeftAndMain implements PermissionProvider {

	private static $url_segment = 'dashboard';
	private static $menu_title = 'Dashboard';
	private static $menu_priority = 1000;

	public function init() {
		parent::init();
		Requirements::css('https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-cms.css');
		Requirements::javascript(DASHBOARD_ADMIN_DIR . '/javascript/dashboard-js.js');

		if ($panelAccentColour = DashboardAdmin::config()->panel_accent_colour) {
			Requirements::customCSS(<<<CSS
.cms-content.DashboardAdmin .dashboard-panel {
	border-top-color: $panelAccentColour;
}
CSS
);
		}
	}

	public function providePermissions() {
		$title = _t('DashboardAdmin.MENUTITLE', LeftAndMain::menu_title_for_class('DashboardAdmin'));
		return array(
			'CMS_ACCESS_DASHBOARDADMIN' => array(
				'name' => _t('CMSMain.ACCESS', "Access to '{title}' section", array('title' => $title)),
				'category' => $title,
				'help' => 'Allow use of the CMS Dashboard'
			)
		);
	}

	public function canView($member = null) {
		return Permission::check('CMS_ACCESS_DASHBOARDADMIN');
	}

	public function DashboardContent() {
		return $this->renderWith('DashboardContent');
	}

	public function DashboardPanels() {
		return $this->renderWith('DashboardPanels');
	}

	public function canViewPanel($panelName) {
		if (class_exists($panelName)) {
			$panel = new $panelName($this);
			return $panel->canView();
		}

		return false;
	}

	public function showPanel($panelName) {
		if (class_exists($panelName)) {
			$panel = new $panelName($this);
			return $panel->forTemplate();
		}

		return false;
	}
}
