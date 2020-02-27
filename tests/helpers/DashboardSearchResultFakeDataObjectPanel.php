<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Model\DashboardSearchResultPanel;

class DashboardSearchResultFakeDataObjectPanel extends DashboardSearchResultPanel
{
    protected $className = FakeDataObject::class;
    protected $searchFields = ['ID'];
    protected $sort = ['ID' => 'ASC'];
}
