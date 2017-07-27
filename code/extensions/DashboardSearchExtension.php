<?php

class DashboardSearchExtension extends Extension
{
    private static $allowed_actions = array(
        'DashboardSearchForm'
    );

    public function DashboardSearchForm()
    {
        $fields = FieldList::create(
            TextField::create('Search', _t('SearchForm.SEARCH', 'Search'))->setAttribute('placeholder', _t('SearchForm.SEARCH', 'Search'))
        );

        $actions = FieldList::create(
            FormAction::create('doDashboardSearch', _t('SearchForm.SEARCH', 'Search'))
        );

        $form = Form::create(
            $this->owner,
            'DashboardSearchForm',
            $fields,
            $actions,
            RequiredFields::create()
        );
        $form->setFormMethod('get');
        $form->setTemplate('DashboardSearchForm');
        $form->addExtraClass('dashboard-search-form');
        $form->disableSecurityToken();
        $form->loadDataFrom($this->owner->getRequest()->getVars());

        return $form;
    }

    public function doDashboardSearch()
    {
        Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-search-panel.css');
        Requirements::javascript(DASHBOARD_ADMIN_DIR . '/javascript/dashboard-search-panel.js');

        $searchValue = $this->owner->getRequest()->getVar('Search');
        $member = Member::CurrentUser();

        $data = array(
            'SearchValue' => Convert::html2raw($searchValue)
        );

        if (!$searchValue) {
            if (Director::is_ajax()) {
                return $this->owner->renderWith('DashboardContent');
            }
            return $this->owner;
        }

        $request = $this->owner->getRequest();

        if ($searchPanelName = $request->getVar('panel-class')) {
            if (Director::is_ajax()) {
                if (class_exists($searchPanelName)) {
                    $searchPanel = new $searchPanelName($this->owner);
                    $searchPanel->performSearch($searchValue);
                    $paginationStart = $request->getVar('start' . $searchPanelName);
                    return $searchPanel->forTemplate($paginationStart);
                }
                return false;
            }
        }

        $searchPanelNames = DashboardAdmin::config()->search_panels;
        $searchClassNames = array();
        $searchResultPanels = ArrayList::create();
        $singleSearchResultItem = null;
        foreach ($searchPanelNames as $searchPanelName) {
            if (!class_exists($searchPanelName)) {
                continue;
            }

            $searchPanel = new $searchPanelName($this->owner);
            if (!$searchPanel->canView($member)) {
                continue;
            }

            $paginationStart = $request->getVar('start' . $searchPanelName);
            $searchClassNames[] = $searchPanel->plural_name();

            $results = $searchPanel->performSearch($searchValue);
            if ($results->count() === 0) {
                continue;
            }

            $searchResultPanels->push(ArrayData::create(array(
                'Results' => $searchPanel->forTemplate($paginationStart)
            )));

            if ($results->count() === 1 && count($searchResultPanels) === 1) {
                $singleSearchResultItem = $results->first();
            } else {
                $singleSearchResultItem = null;
            }
        }

        if ($singleSearchResultItem && $singleSearchResultItem->config()->dashboard_automatic_search_redirect) {
            if ($searchResultCMSLink = $singleSearchResultItem->getSearchResultCMSLink()) {
                return $this->owner->redirect($searchResultCMSLink);
            }
        }

        if (count($searchClassNames)) {
            $data['SearchMessage'] = _t('SearchPanel.SEARCHINGFOR', 'Searching for') . ' ';
            $lastClassName = array_pop($searchClassNames);
            if (count($searchClassNames)) {
                $data['SearchMessage'] .= implode(', ', $searchClassNames) . ' &amp; ' . $lastClassName;
            } else {
                $data['SearchMessage'] .= $lastClassName;
            }
        }
        $data['SearchResults'] = $searchResultPanels;
        $this->owner->customise($data);

        if (Director::is_ajax()) {
            return $this->owner->customise(array('DashboardPanels' => $this->owner->renderWith('SearchPanel')))->renderWith('DashboardContent');
        }

        return $this->owner->customise(array('DashboardPanels' => $this->owner->renderWith('SearchPanel')));
    }
}
