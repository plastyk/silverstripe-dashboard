<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Panels\UsefulLinksPanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class UsefulLinksPanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateUsefulLinksPanel()
    {
        $usefulLinksPanel = new UsefulLinksPanel();
        $this->assertNotNull($usefulLinksPanel);
    }

    public function testCanView()
    {
        $usefulLinksPanel = UsefulLinksPanel::singleton();

        $this->assertTrue($usefulLinksPanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($usefulLinksPanel->canView());
    }
}
