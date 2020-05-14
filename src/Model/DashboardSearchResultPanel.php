<?php

namespace Plastyk\Dashboard\Model;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\View\SSViewer;

abstract class DashboardSearchResultPanel
{
    use Extensible;
    use Injectable;
    use Configurable;

    protected $controller;
    protected $className;
    protected $results;
    protected $searchFields = ['Title'];
    protected $sort = ['Created' => 'ASC'];
    protected $exclusions = [];

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

    public function getSingularName()
    {
        return $this->getName('singular');
    }

    public function getPluralName()
    {
        return $this->getName('plural');
    }

    private function getName($nameType)
    {
        $nameType .= '_name';

        $searchResultClass = Injector::inst()->get($this->getClassName());

        if (ClassInfo::hasMethod($searchResultClass, $nameType)) {
            return $searchResultClass->$nameType();
        }

        return false;
    }

    public function forTemplate($paginationStart = 0)
    {
        $class = get_class($this);
        $ancestry = ClassInfo::ancestry($class);
        array_reverse($ancestry);
        $template = new SSViewer($ancestry);

        $data = [
            'ClassName' => $this->getClassName(),
            'PanelClassName' => str_replace('\\', '-', $class),
            'Results' => $this->getPaginatedResults($paginationStart),
        ];

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
        $paginatedResults = new PaginatedList($this->getResults(), [$paginationStartLabel => $paginationStart]);
        $paginatedResults->setPagelength(DashboardAdmin::config()->search_results_page_length);
        $paginatedResults->setPaginationGetVar($paginationStartLabel);

        return $paginatedResults;
    }

    public function performSearch($searchValue)
    {
        $searchValue = Convert::raw2sql(strtolower($searchValue));
        $className = $this->getClassName();
        $member = Security::getCurrentUser();

        // Get the where-clause template for the search fields
        $searchWhereFields = [];
        foreach ($this->searchFields as $searchField) {
            $searchWhereFields[] = 'LOWER("' . $searchField . "\") LIKE '%[search-string]%'";
        }
        $searchWhereFieldsTemplate = implode(' OR ', $searchWhereFields);
        $searchExactMatch = str_replace('[search-string]', $searchValue, $searchWhereFieldsTemplate);

        $searchWords = explode(' ', $searchValue);
        $searchWhereList = [];
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
                ->exclude([
                    'ID' => $exactItems->column('ID'),
                ])
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
