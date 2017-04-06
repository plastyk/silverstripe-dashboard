<?php

class MoreInformationPanel extends DashboardPanel {

	public function canView($member = null) {
		return Permission::check('CMS_ACCESS_ADMIN');
	}

	public function init() {
		parent::init();
		Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-more-information-panel.css');
	}

	public function getData() {
		$data = parent::getData();

		$data['DashboardContactEmail'] = DashboardAdmin::config()->contact_email ? : false;
		$data['DashboardContactName'] = DashboardAdmin::config()->contact_name ? : false;

		return $data;
	}
}
