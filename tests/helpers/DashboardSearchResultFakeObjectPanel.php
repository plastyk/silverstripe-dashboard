<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Model\DashboardSearchResultPanel;

class DashboardSearchResultFakeObjectPanel extends DashboardSearchResultPanel
{
    protected $className = FakeObject::class;
    protected $searchFields = ['ID'];
    protected $sort = ['ID' => 'ASC'];
}
