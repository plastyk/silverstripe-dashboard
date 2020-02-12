<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Search\DashboardSearchResultPagePanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class DashboardSearchResultPagePanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateDashboardSearchResultPagePanel()
    {
        $dashboardSearchResultPagePanel = new DashboardSearchResultPagePanel();
        $this->assertNotNull($dashboardSearchResultPagePanel);
    }

    public function testCanView()
    {
        $dashboardSearchResultPagePanel = DashboardSearchResultPagePanel::singleton();

        $this->assertTrue($dashboardSearchResultPagePanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($dashboardSearchResultPagePanel->canView());
    }
}
