<?php

abstract class DashboardSearchResultPanel extends Object
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
    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->results = false;
        $this->paginatedResults = false;
    }

    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_DASHBOARDADMIN') && class_exists($this->className);
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function singular_name()
    {
        if ($this->singular_name) {
            return $this->singular_name;
        }
        $searchResultClass = Object::singleton($this->getClassName());
        if (method_exists($searchResultClass, 'singular_name')) {
            return $searchResultClass->singular_name();
        }
        return false;
    }

    public function plural_name()
    {
        if ($this->plural_name) {
            return $this->plural_name;
        }
        $searchResultClass = Object::singleton($this->getClassName());
        if (method_exists($searchResultClass, 'plural_name')) {
            return $searchResultClass->plural_name();
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
            'PanelClassName' => $class,
            'Results' => $this->getPaginatedResults($paginationStart)
        );

        return $template->process($this->controller, $data);
    }

    public function getResults()
    {
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
        $member = Member::currentUser();

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
        $likeItems = $className::get()->where($searchWordMatch)
            ->exclude($this->exclusions)
            ->exclude(array(
                'Id' => $exactItems->column('ID')
            ))
            ->sort($this->sort);
        $likeItems->filterByCallback(function ($item) use ($member) {
            return $item->canView($member);
        });

        $exactItems->merge($likeItems);
        $this->results = $exactItems;
        return $this->results;
    }
}
