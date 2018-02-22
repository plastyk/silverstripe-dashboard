<?php

class RecentlyEditedPagesPanel extends DashboardPanel
{
    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_CMSMain') && class_exists('CMSPagesController') && parent::canView($member);
    }

    public function getData()
    {
        $data = parent::getData();

        $data['Results'] = $this->Results();

        return $data;
    }

    public function Results()
    {
        return Page::get()->filter('LastEdited:GreaterThan', date('c', strtotime('-6 months')))->sort('LastEdited DESC')->limit(8);
    }
}
