<?php

class MoreInformationPanel extends DashboardPanel
{
    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_ADMIN');
    }

    public function init()
    {
        parent::init();
        Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-more-information-panel.css');
    }

    public function getData()
    {
        $data = parent::getData();

        $data['ContactEmail'] = DashboardAdmin::config()->contact_email ?: false;
        $data['ContactName'] = DashboardAdmin::config()->contact_name ?: _t('MoreInformationPanel.YOURWEBDEVELOPER', 'your web developer');
        $data['Content'] = $this->getContent();

        return $data;
    }

    public function getContent()
    {
        $contactEmail = DashboardAdmin::config()->contact_email ?: false;
        $contactName = DashboardAdmin::config()->contact_name ?: _t('MoreInformationPanel.YOURWEBDEVELOPER', 'your web developer');

        if ($contactEmail) {
            $contactName = '<a href="mailto:' . $contactEmail . '">' . $contactName . '</a>';
        }

        $content = _t(
            'MoreInformationPanel.MOREINFORMATIONMESSAGE',
            'Custom dashboard panels are available. Contact {contactName} if you would like to discuss.',
            'More information message',
            array('contactName' => $contactName)
        );

        return DBField::create_field('HTMLText', $content);
    }
}
