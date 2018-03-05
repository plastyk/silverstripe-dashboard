<?php

namespace Plastyk\Dashboard\Model;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\View\SSViewer;

class DashboardSearchResultPanel extends DataObject
{
    protected $controller;
    protected $className;
    protected $results;
    protected $singular_name;
    protected $plural_name;
    protected $searchFields = array('Title');
    protected $sort = array('Created' => 'ASC');
    protected $exclusions = array();

    /**
     * @param DasboardAdmin $controller
     */
    public function __construct($controller = null)
    {
        $this->controller = $controller;
        $this->results = false;
    }

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_DASHBOARDADMIN') && class_exists($this->className);
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function singular_name()
    {
        return $this->getName('singular');
    }

    public function plural_name()
    {
        return $this->getName('plural');
    }

    private function getName($nameType)
    {
        $nameType .= '_name';
        if ($this->$nameType) {
            return $this->$nameType;
        }
        $searchResultClass = Injectable::singleton($this->getClassName());
        if (method_exists($this, $nameType)) {
            return $searchResultClass->$nameType();
        }
        return false;
    }

    public function forTemplate($paginationStart = 0)
    {
        $class = get_class($this);
        $ancestry = ClassInfo::ancestry($class);
        $ancestry = array_slice($ancestry, 2);
        array_reverse($ancestry);
        $template = new SSViewer($ancestry);

        $data = array(
            'ClassName' => $this->getClassName(),
            'PanelClassName' => str_replace('\\', '-', $class),
            'Results' => $this->getPaginatedResults($paginationStart)
        );

        return $template->process($this->controller, $data);
    }

    public function getResults()
    {
        if (!$this->results) {
            return ArrayList::create();
        }
        return $this->results;
    }

    private function getPaginatedResults($paginationStart = 0)
    {
        $paginationStartLabel = 'start' . get_class($this);
        $paginatedResults = new PaginatedList($this->getResults(), array($paginationStartLabel => $paginationStart));
        $paginatedResults->setPagelength(DashboardAdmin::config()->search_results_page_length);
        $paginatedResults->setPaginationGetVar($paginationStartLabel);
        return $paginatedResults;
    }

    public function performSearch($searchValue)
    {
        $searchValue = Convert::raw2sql($searchValue);
        $className = $this->getClassName();
        $member = Security::getCurrentUser();

        // Get the where-clause template for the search fields
        $searchWhereFields = array();
        foreach ($this->searchFields as $searchField) {
            $searchWhereFields[] = $searchField . " LIKE '%[search-string]%'";
        }
        $searchWhereFieldsTemplate = implode(' OR ', $searchWhereFields);
        $searchExactMatch = str_replace('[search-string]', $searchValue, $searchWhereFieldsTemplate);

        $searchWords = explode(' ', $searchValue);
        $searchWhereList = array();
        foreach ($searchWords as $searchWord) {
            $searchWhereList[] = '(' . str_replace('[search-string]', $searchWord, $searchWhereFieldsTemplate) . ')';
        }
        $searchWordMatch = implode(' AND ', $searchWhereList);

        // Perform exact match search
        $exactItems = $className::get()->where($searchExactMatch)
            ->exclude($this->exclusions)
            ->sort($this->sort);
        $exactItems = $exactItems->filterByCallback(function ($item) use ($member) {
            return $item->canView($member);
        });

        // Perform word match search
        if ($exactItems->count()) {
            $likeItems = $className::get()->where($searchWordMatch)
                ->exclude($this->exclusions)
                ->exclude(array(
                    'ID' => $exactItems->column('ID')
                ))
                ->sort($this->sort);
        } else {
            $likeItems = $className::get()->where($searchWordMatch)
                ->exclude($this->exclusions)
                ->sort($this->sort);
        }
        $likeItems->filterByCallback(function ($item) use ($member) {
            return $item->canView($member);
        });

        $exactItems->merge($likeItems);
        $this->results = $exactItems;
        return $this->results;
    }
}
