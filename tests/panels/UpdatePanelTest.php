<?php

namespace Plastyk\Dashboard\Tests\Panels;

use Plastyk\Dashboard\Panels\UpdatePanel;
use SilverStripe\Dev\FunctionalTest;

class UpdatePanelTest extends FunctionalTest
{
    protected static $fixture_file = 'dashboard/tests/panels/UpdatePanelTest.yml';

    public function testPermission()
    {
        $updatePanel = UpdatePanel::create();

        $nonPermittedUser = $this->objFromFixture('SilverStripe\Security\Member', 'user1');
        $this->logInAs($nonPermittedUser);
        $this->assertFalse($updatePanel->canView());
    }
}
