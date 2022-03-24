<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Panels\MoreInformationPanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class DashboardPanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateDashboardPanel()
    {
        $moreInformationPanel = new MoreInformationPanel();
        $this->assertNotNull($moreInformationPanel);
    }

    public function testCanView()
    {
        $moreInformationPanel = MoreInformationPanel::singleton();

        $this->assertTrue($moreInformationPanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($moreInformationPanel->canView());
    }

    public function testForTemplate()
    {
        $moreInformationPanel = MoreInformationPanel::singleton();

        $this->assertStringContainsString('Custom dashboard panels are available', $moreInformationPanel->forTemplate());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($moreInformationPanel->forTemplate());
    }
}
