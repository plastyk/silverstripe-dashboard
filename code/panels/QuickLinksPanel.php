<?php

class QuickLinksPanel extends DashboardPanel
{
    public function canView($member = null)
    {
        $data = $this->getData();
        if (!$data['CanView']) {
            return false;
        }

        return parent::canView($member);
    }

    public function init()
    {
        parent::init();
        Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-quick-links-panel.css');
    }

    public function getData()
    {
        $member = Member::currentUserID();

        $data = parent::getData();

        $data['CanView'] = false;

        $data['CanViewPages'] = Permission::checkMember($member, 'CMS_ACCESS_CMSMain') && class_exists('CMSPagesController');
        $data['CanView'] = $data['CanView'] || $data['CanViewPages'];
        $data['CanViewUsers'] = Permission::checkMember($member, 'CMS_ACCESS_SecurityAdmin') && class_exists('SecurityAdmin');
        $data['CanView'] = $data['CanView'] || $data['CanViewUsers'];
        $data['CanViewSettings'] = Permission::checkMember($member, 'EDIT_SITECONFIG') && class_exists('SiteConfigLeftAndMain');
        $data['CanView'] = $data['CanView'] || $data['CanViewSettings'];

        $this->extend('updateData', $data);

        return $data;
    }
}
