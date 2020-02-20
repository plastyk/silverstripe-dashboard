<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Panels\UpdatePanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class UpdatePanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateUpdatePanel()
    {
        $updatePanel = new UpdatePanel();
        $this->assertNotNull($updatePanel);
    }

    public function testCanView()
    {
        $updatePanel = UpdatePanel::singleton();

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($updatePanel->canView());
    }
}
