<?php

class SearchPanel extends DashboardPanel {

	public function getData() {
		$data = parent::getData();

		$data['DashboardSearchForm'] = $this->controller->DashboardSearchForm();
		$data['SearchValue'] = false;

		return $data;
	}

	public function init() {
		parent::init();
		Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-search-panel.css');
		Requirements::javascript(DASHBOARD_ADMIN_DIR . '/javascript/dashboard-search-panel.js');
	}

}
