<?php

namespace Plastyk\Dashboard\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\View\SSViewer;

class DashboardSearchResultExtension extends DataExtension
{
    public function getSearchResultCMSLink()
    {
        $adminLink = $this->owner->config()->dashboard_admin_link;

        if (!$adminLink) {
            return '';
        }

        return SSViewer::execute_string($adminLink, $this->owner);
    }
}
