<?php

namespace Plastyk\Dashboard\Extensions;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Security\Security;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

class DashboardSearchExtension extends Extension
{
    private static $allowed_actions = [
        'DashboardSearchForm',
    ];

    public function DashboardSearchForm()
    {
        $fields = FieldList::create(
            TextField::create('Search', _t('SearchForm.SEARCH', 'Search'))
                ->setAttribute('placeholder', _t('SearchForm.SEARCH', 'Search'))
                ->addExtraClass('search-query form-group--no-label')
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
        $form->setTemplate('Includes/DashboardSearchForm');
        $form->addExtraClass('dashboard-search-form');
        $form->disableSecurityToken();
        $form->loadDataFrom($this->owner->getRequest()->getVars());

        return $form;
    }

    public function doDashboardSearch()
    {
        Requirements::css('plastyk/dashboard:css/dashboard-search-panel.css');
        Requirements::javascript('plastyk/dashboard:javascript/dashboard-search-panel.js');

        $request = $this->owner->getRequest();
        $searchValue = $request->getVar('Search');
        $member = Security::getCurrentUser();
        $searchPanelNames = DashboardAdmin::config()->search_panels;

        if (!$searchPanelNames) {
            $searchPanelNames = DashboardAdmin::config()->default_search_panels;
        }

        $data = [
            'SearchValue' => Convert::html2raw($searchValue),
        ];

        if (!$searchValue) {
            if (Director::is_ajax()) {
                return $this->owner->renderWith('Includes/DashboardContent');
            }

            return $this->owner;
        }

        if (Director::is_ajax() && $specificSearchPanel = $request->getVar('panel-class')) {
            $searchPanel = $this->doPanelSearch($specificSearchPanel, $member, $searchValue);
            if ($searchPanel) {
                return $searchPanel->Panel;
            }

            return false;
        }

        $searchResultPanels = ArrayList::create();
        foreach ($searchPanelNames as $searchPanelName) {
            $searchPanel = $this->doPanelSearch($searchPanelName, $member, $searchValue);
            if ($searchPanel) {
                $searchResultPanels->push($searchPanel);
            }
        }

        $numberOfResultsAcrossPanels = array_sum($searchResultPanels->column('ResultCount'));
        if ($numberOfResultsAcrossPanels == 1) {
            $singleResultPanel = $searchResultPanels->filterByCallback(function ($item) {
                return $item->ResultCount == 1
                    && $item->FirstResult->config()->dashboard_automatic_search_redirect;
            })->first();

            if ($singleResultPanel) {
                $searchResultCMSLink = $singleResultPanel->FirstResult->getSearchResultCMSLink();

                if ($searchResultCMSLink) {
                    return $this->owner->redirect($searchResultCMSLink);
                }
            }
        }

        $searchClassNames = $searchResultPanels->column('SearchName');
        if (count($searchClassNames) > 1) {
            $lastClassName = array_pop($searchClassNames);
            $data['SearchMessage'] = _t('SearchPanel.SEARCHINGFOR', 'Searching for')
                . ' ' . implode(', ', $searchClassNames) . ' & ' . $lastClassName;
        } elseif (count($searchClassNames) == 1) {
            $data['SearchMessage'] = _t('SearchPanel.SEARCHINGFOR', 'Searching for')
                . ' ' . $searchClassNames[0];
        }

        $data['SearchResultPanels'] = $searchResultPanels->exclude('ResultCount', 0);
        $this->owner->customise($data);
        $this->owner->customise([
            'DashboardPanels' => $this->owner->renderWith('SearchPanel'),
        ]);

        if (Director::is_ajax()) {
            return $this->owner->renderWith('Includes/DashboardContent');
        }

        return $this->owner;
    }

    private function doPanelSearch($searchPanelName, $member, $searchValue)
    {
        if (!class_exists($searchPanelName)) {
            return false;
        }

        $searchPanel = $searchPanelName::create($this->owner);
        if (!$searchPanel->canView($member)) {
            return false;
        }

        $paginationStart = $this->owner->getRequest()->getVar('start' . $searchPanelName);
        $results = $searchPanel->performSearch($searchValue);

        return ArrayData::create([
            'SearchName' => $searchPanel->getPluralName(),
            'ResultCount' => $results->count(),
            'FirstResult' => $results->first(),
            'Panel' => $searchPanel->forTemplate($paginationStart),
        ]);
    }
}
