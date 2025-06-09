<?php

namespace Plastyk\Dashboard\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\TemplateEngine\SSTemplateEngine;
use SilverStripe\View\ViewLayerData;

class DashboardSearchResultExtension extends Extension
{
    public function getSearchResultCMSLink()
    {
        $adminLink = $this->owner->config()->dashboard_admin_link;

        if (!$adminLink) {
            return '';
        }

        return SSTemplateEngine::singleton()->renderString(
            $adminLink, 
            ViewLayerData::create($this->owner)
        );
    }
}
