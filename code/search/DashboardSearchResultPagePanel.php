<?php

class DashboardSearchResultPagePanel extends DashboardSearchResultPanel
{
    protected $className = 'Page';
    protected $searchFields = array('Title', 'Content');
    protected $sort = array('Title' => 'ASC');

    public function canView($member = null)
    {
        return parent::canView($member) && Permission::check('CMS_ACCESS_CMSMain') && class_exists('CMSPagesController');
    }
}
