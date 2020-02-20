<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Search\DashboardSearchResultFilePanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class DashboardSearchResultFilePanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateDashboardSearchResultFilePanel()
    {
        $dashboardSearchResultFilePanel = new DashboardSearchResultFilePanel();
        $this->assertNotNull($dashboardSearchResultFilePanel);
    }

    public function testCanView()
    {
        $dashboardSearchResultFilePanel = DashboardSearchResultFilePanel::singleton();

        $this->assertTrue($dashboardSearchResultFilePanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($dashboardSearchResultFilePanel->canView());
    }
}
