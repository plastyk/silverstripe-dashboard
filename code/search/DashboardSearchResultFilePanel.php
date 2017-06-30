<?php

class DashboardSearchResultFilePanel extends DashboardSearchResultPanel
{
    protected $className = 'File';
    protected $searchFields = array('Title', 'Name', 'Content', 'Filename');
    protected $sort = array('Title' => 'ASC');
    protected $exclusions = array('ClassName' => 'Folder');

    public function canView($member = null)
    {
        return parent::canView($member) && Permission::check('CMS_ACCESS_AssetAdmin') && class_exists('AssetAdmin');
    }
}
