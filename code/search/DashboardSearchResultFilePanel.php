<?php

class DashboardSearchResultFilePanel extends DashboardSearchResultPanel {

	public function __construct($controller) {
		parent::__construct($controller, 'File');
	}

	public function canView($member = null) {
		return parent::canView($member) && Permission::check('CMS_ACCESS_AssetAdmin') && class_exists('AssetAdmin');
	}

	public function performSearch($searchValue, $request = array(), $searchFields = array('Title', 'Name', 'Content', 'Filename'), $sort = 'Title', $exclusions = array('ClassName' => 'Folder')) {
		return parent::performSearch($searchValue, $request, $searchFields, $sort, $exclusions);
	}
}
