<?php

abstract class DashboardSearchResultPanel extends Object
{
    protected $controller;
    protected $className;
    protected $results;
    protected $paginatedResults;
    protected $singular_name;
    protected $plural_name;

    /**
     * @param string $className
     */
    public function __construct($controller, $className)
    {
        $this->controller = $controller;
        $this->className = $className;
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
        $resultsSearchClassName = $this->className;
        if (class_exists($resultsSearchClassName)) {
            $resultsSearchClass = new $resultsSearchClassName();
            return $resultsSearchClass->singular_name();
        }
        return false;
    }

    public function plural_name()
    {
        if ($this->plural_name) {
            return $this->plural_name;
        }
        $resultsSearchClassName = $this->className;
        if (class_exists($resultsSearchClassName)) {
            $resultsSearchClass = new $resultsSearchClassName();
            return $resultsSearchClass->plural_name();
        }
        return false;
    }

    public function getPanelClassName()
    {
        return get_class($this);
    }

    public function forTemplate()
    {
        $panelClassName = $this->getPanelClassName();
        $template = new SSViewer($panelClassName);

        $data = array(
            'ClassName' => $this->getClassName(),
            'PanelClassName' => $panelClassName,
            'Results' => $this->paginatedResults
        );

        return $template->process($this->controller, $data);
    }

    public function performSearch($searchValue, $paginationStart = 0, $searchFields = array('Title'), $sort = array('Created' => 'ASC'), $exclusions = array())
    {
        $searchWhereClause = '';
        $searchWords = explode(' ', $searchValue);
        $notFirstWord = false;
        $notFirstField = false;
        $className = $this->className;

        $searchWhereClauseTemplate = '';
        foreach ($searchFields as $searchField) {
            $searchWhereClauseTemplate .= ($notFirstField ? ' OR ' : '') . $searchField . " LIKE '%[search-string]%' ";
            $notFirstField = true;
        }

        foreach ($searchWords as $searchWord) {
            $searchWhereClause .= ($notFirstWord ? ' AND ' : '') . ' ( ';
            $searchWhereClause .= str_replace('[search-string]', $searchWord, $searchWhereClauseTemplate);
            $searchWhereClause .= ' ) ';
            $notFirstWord = true;
        }

        $member = Member::currentUser();

        // Search exact items
        $exactItems = $className::get()->where(
            str_replace('[search-string]', $searchValue, $searchWhereClauseTemplate)
        )->exclude($exclusions)->sort($sort);
        $exactItems = $exactItems->filterByCallback(function($item) use ($member) {
            return $item->canEdit($member);
        });

        $items = $className::get()->where($searchWhereClause)->exclude($exclusions)->sort($sort);
        $items = $items->filterByCallback(function($item) use ($member) {
            return $item->canEdit($member);
        });

        $exactItems->merge($items);
        $exactItems->removeDuplicates();

        $this->results = $exactItems;
        $this->paginatedResults = $exactItems;

        if ($exactItems) {
            $this->paginatedResults = new PaginatedList($exactItems, array('start' . $this->getPanelClassName() => $paginationStart));
            $this->paginatedResults->setPagelength(10);
            $this->paginatedResults->setPaginationGetVar('start' . $this->getPanelClassName());
            return $this->paginatedResults;
        }

        return $exactItems;
    }
}
