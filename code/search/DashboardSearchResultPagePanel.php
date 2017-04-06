<?php

class DashboardSearchResultPagePanel extends DashboardSearchResultPanel {

	public function __construct($controller) {
		parent::__construct($controller, 'Page');
	}

	public function canView($member = null) {
		return parent::canView($member) && Permission::check('CMS_ACCESS_CMSMain') && class_exists('CMSPagesController');
	}

	function performSearch($searchValue, $request = array(), $searchFields = array('Title', 'Content'), $sort = 'Title', $exclusions = array()) {
		return parent::performSearch($searchValue, $request, $searchFields, $sort, $exclusions);
	}
}
