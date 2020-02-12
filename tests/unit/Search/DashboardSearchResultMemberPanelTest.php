<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Search\DashboardSearchResultMemberPanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class DashboardSearchResultMemberPanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateDashboardSearchResultMemberPanel()
    {
        $dashboardSearchResultMemberPanel = new DashboardSearchResultMemberPanel();
        $this->assertNotNull($dashboardSearchResultMemberPanel);
    }

    public function testCanView()
    {
        $dashboardSearchResultMemberPanel = DashboardSearchResultMemberPanel::singleton();

        $this->assertTrue($dashboardSearchResultMemberPanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($dashboardSearchResultMemberPanel->canView());
    }
}
