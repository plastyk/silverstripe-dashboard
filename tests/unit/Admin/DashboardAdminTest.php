<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use Plastyk\Dashboard\Panels\MoreInformationPanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class DashboardAdminTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testPermission()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $permittedUser = $this->objFromFixture(Member::class, 'user1');
        $this->logInAs($permittedUser);
        $this->assertTrue($dashboardAdmin->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);
        $this->assertFalse($dashboardAdmin->canView());
    }

    public function testProvidePermissions()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $permissions = $dashboardAdmin->providePermissions();

        $this->assertTrue(isset($permissions['CMS_ACCESS_DASHBOARDADMIN']));
        $this->assertTrue(isset($permissions['CMS_ACCESS_DASHBOARDADMIN']['category']));
        $this->assertEquals('Dashboard', $permissions['CMS_ACCESS_DASHBOARDADMIN']['category']);
    }

    public function testGetDashboardContent()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $dashboardContent = $dashboardAdmin->getDashboardContent();

        $this->assertContains('dashboardadmin-cms-content', $dashboardContent);
    }

    public function testGetDashboardPanels()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $dashboardPanels = $dashboardAdmin->getDashboardPanels();

        $this->assertContains('<h1>Your Site Name</h1>', $dashboardPanels);
    }

    public function testCanViewPanel()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $this->assertFalse($dashboardAdmin->canViewPanel('FakePanelThatDoesNotExist'));
        $this->assertTrue($dashboardAdmin->canViewPanel(MoreInformationPanel::class));

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($dashboardAdmin->canViewPanel(MoreInformationPanel::class));
    }

    public function testShowPanel()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $this->assertFalse($dashboardAdmin->showPanel('FakePanelThatDoesNotExist'));

        $panel = $dashboardAdmin->showPanel(MoreInformationPanel::class);
        $this->assertContains('more-information-panel', $panel->value);

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($dashboardAdmin->showPanel(MoreInformationPanel::class));
    }
}
