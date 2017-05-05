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
	}

	public function providePermissions() {
		return array(
			'CMS_ACCESS_DASHBOARDADMIN' => array(
				'name' => 'Access to \'Dashboard\' section',
				'category' => 'Dashboard',
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
