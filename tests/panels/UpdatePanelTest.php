<?php

class UpdatePanelTest extends FunctionalTest
{
    protected static $fixture_file = 'dashboard/tests/panels/UpdatePanelTest.yml';

    public function testPermission()
    {
        $updatePanel = UpdatePanel::create();

        $nonPermittedUser = $this->objFromFixture('Member', 'user1');
        $this->logInAs($nonPermittedUser);
        $this->assertFalse($updatePanel->canView());
    }
}
