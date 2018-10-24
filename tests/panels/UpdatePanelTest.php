<?php

namespace Plastyk\Dashboard\Tests\Panels;

use Plastyk\Dashboard\Panels\UpdatePanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class UpdatePanelTest extends SapphireTest
{
    protected static $fixture_file = 'UpdatePanelTest.yml';

    public function testPermission()
    {
        $updatePanel = UpdatePanel::create();

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user1');
        $this->logInAs($nonPermittedUser);
        $this->assertFalse($updatePanel->canView());
    }
}
