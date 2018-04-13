<?php

namespace Plastyk\Dashboard\Panels;

use Plastyk\Dashboard\Model\DashboardPanel;
use SilverStripe\View\Requirements;

class SearchPanel extends DashboardPanel
{
    public function getData()
    {
        $data = parent::getData();

        $data['DashboardSearchForm'] = $this->controller->DashboardSearchForm();
        $data['SearchValue'] = false;

        return $data;
    }

    public function init()
    {
        parent::init();
        Requirements::css('plastyk/dashboard:css/dashboard-search-panel.css');
        Requirements::javascript('plastyk/dashboard:javascript/dashboard-search-panel.js');
    }
}
