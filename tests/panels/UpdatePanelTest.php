<?php

namespace Plastyk\Dashboard\Tests\Panels;

use Plastyk\Dashboard\Panels\UpdatePanel;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\Member;

class UpdatePanelTest extends FunctionalTest
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
