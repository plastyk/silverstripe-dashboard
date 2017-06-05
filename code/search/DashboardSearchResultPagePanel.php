<?php

class DashboardSearchResultPagePanel extends DashboardSearchResultPanel {

	public function __construct($controller) {
		parent::__construct($controller, 'Page');
	}

	public function canView($member = null) {
		return parent::canView($member) && Permission::check('CMS_ACCESS_CMSMain') && class_exists('CMSPagesController');
	}

	public function performSearch($searchValue, $paginationStart = 0, $searchFields = array('Title', 'Content'), $sort = array('Title' => 'ASC'), $exclusions = array()) {
		return parent::performSearch($searchValue, $paginationStart, $searchFields, $sort, $exclusions);
	}
}
