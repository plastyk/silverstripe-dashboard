<?php

class DashboardSearchResultMemberPanel extends DashboardSearchResultPanel {

	public function __construct($controller) {
		parent::__construct($controller, 'Member');
	}

	public function canView($member = null) {
		return parent::canView($member) && Permission::check('CMS_ACCESS_SecurityAdmin') && class_exists('SecurityAdmin');
	}

	public function performSearch($searchValue, $request = array(), $searchFields = array('FirstName', 'Surname', 'Email'), $sort = array('FirstName' => 'ASC', 'Surname' => 'ASC', 'Email' => 'ASC'), $exclusions = array()) {
		return parent::performSearch($searchValue, $request, $searchFields, $sort, $exclusions);
	}
}
