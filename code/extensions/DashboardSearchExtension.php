<?php

class DashboardSearchExtension extends Extension {

	private static $allowed_actions = array(
		'DashboardSearchForm'
	);

	private static $search_classes = array();

	function DashboardSearchForm() {

		$fields = FieldList::create(
			TextField::create('Search', _t('SearchForm.SEARCH','Search'))->setAttribute('placeholder', _t('SearchForm.SEARCH','Search'))
		);

		$actions = FieldList::create(
			FormAction::create('doDashboardSearch', _t('SearchForm.SEARCH','Search'))
		);

		$requiredFields = RequiredFields::create(
		);

		$form = Form::create(
			$this->owner,
			'DashboardSearchForm',
			$fields,
			$actions,
			$requiredFields
		);
		$form->setFormMethod('get');
		$form->setTemplate('DashboardSearchForm');
		$form->addExtraClass('dashboard-search-form');
		$form->disableSecurityToken();
		$form->loadDataFrom($this->owner->getRequest()->getVars());

		return $form;
	}

	function doDashboardSearch() {
		Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-search-panel.css');
		Requirements::javascript(DASHBOARD_ADMIN_DIR . '/javascript/dashboard-search-panel.js');

		$searchValue = Convert::raw2sql($this->owner->getRequest()->getVar('Search'));
		$member = Member::CurrentUser();

		$data = array(
			'SearchValue' =>  Convert::html2raw($searchValue)
		);

		if (!$searchValue) {
			if (Director::is_ajax()) {
				return $this->owner->renderWith('DashboardContent');
			}
			return $this->owner;
		}

		if ($searchPanelName = $this->owner->getRequest()->getVar('panel-class')) {
			if (Director::is_ajax()) {
				if (class_exists($searchPanelName)) {
					$searchPanel = new $searchPanelName($this->owner);
					$results = $searchPanel->performSearch($searchValue, $this->owner->request->getVar('start' . $searchPanelName));
					return $searchPanel->forTemplate();
				}
				return false;
			}
		}

		$searchPanelNames = DashboardAdmin::config()->search_panels;
		$searchMessageClasses = array();
		$searchResults = ArrayList::create();
		foreach($searchPanelNames as $searchPanelName) {
			if (class_exists($searchPanelName)) {
				$searchPanel = new $searchPanelName($this->owner);
				if ($searchPanel->canView($member)) {
					$searchMessageClasses[] = $searchPanel->plural_name();
					$results = $searchPanel->performSearch($searchValue, $this->owner->request->getVar('start' . $searchPanelName));
					if ($results->count()) {
						$searchResults->push(ArrayData::create(array(
							'Results' => $searchPanel->forTemplate()
						)));
					}
				}
			}
		}

		if (count($searchMessageClasses)) {
			$data['SearchMessage'] = 'Searching for ' . strrev(implode(strrev(' &amp; '), explode(strrev(', '), strrev(implode(', ', $searchMessageClasses)), 2)));
		}
		$data['SearchResults'] = $searchResults;

		$this->owner->customise($data);

		if (Director::is_ajax()) {
			return $this->owner->customise(array('DashboardPanels' => $this->owner->renderWith('SearchPanel')))->renderWith('DashboardContent');
		}

		return $this->owner->customise(array('DashboardPanels' => $this->owner->renderWith('SearchPanel')));
	}

}
