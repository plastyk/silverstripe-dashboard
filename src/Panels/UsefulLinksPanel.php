<?php

namespace Plastyk\Dashboard\Panels;

use Plastyk\Dashboard\Model\DashboardPanel;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\ArrayList;

class UsefulLinksPanel extends DashboardPanel
{
    private static $enabled = false;

    private static $title = 'Tools and tips';

    private static $links;

    public function getData()
    {
        $data = parent::getData();

        $data['Links'] = ArrayList::create(Config::inst()->get($this::class, 'links'));
        $data['Title'] = Config::inst()->get($this::class, 'title');

        return $data;
    }
}
