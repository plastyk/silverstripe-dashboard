<?php

class DashboardSearchResultMemberPanel extends DashboardSearchResultPanel
{
    protected $className = 'Member';
    protected $searchFields = array('FirstName', 'Surname', 'Email');
    protected $sort = array('FirstName' => 'ASC', 'Surname' => 'ASC', 'Email' => 'ASC');

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_SecurityAdmin') && class_exists('SecurityAdmin') && parent::canView($member);
    }
}
