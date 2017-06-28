<?php

class UpdatePanelTest extends FunctionalTest
{
    protected static $fixture_file = 'dashboard/tests/panels/UpdatePanelTest.yml';

    public function testPermission()
    {
        $updatePanel = UpdatePanel::create();

        $permittedUser = $this->objFromFixture('Member', 'user1');
        $this->logInAs($permittedUser);
        $this->assertTrue($updatePanel->canView());

        $nonPermittedUser = $this->objFromFixture('Member', 'user2');
        $this->logInAs($nonPermittedUser);
        $this->assertFalse($updatePanel->canView());
    }

    public function testVersionLevel()
    {
        $updatePanel = UpdatePanel::create();

        $this->assertEquals('major', $updatePanel->getVersionLevelDifference('3.0.0', '4.0.0'));
        $this->assertEquals('major', $updatePanel->getVersionLevelDifference('3.1.0', '4.5.0'));
        $this->assertEquals('major', $updatePanel->getVersionLevelDifference('3.6.1', '4.0.0'));
        $this->assertEquals('minor', $updatePanel->getVersionLevelDifference('3.0.0', '3.1.0'));
        $this->assertEquals('minor', $updatePanel->getVersionLevelDifference('3.1.0', '3.5.0'));
        $this->assertEquals('minor', $updatePanel->getVersionLevelDifference('3.5.1', '3.6.0'));
        $this->assertEquals('security', $updatePanel->getVersionLevelDifference('3.0.0', '3.0.1'));
        $this->assertEquals('security', $updatePanel->getVersionLevelDifference('3.1.2', '3.1.5'));

        $this->assertFalse($updatePanel->getVersionLevelDifference('3.6.0', '3.6.0'));
        $this->assertFalse($updatePanel->getVersionLevelDifference('4.0.0', '3.6.0'));
        $this->assertFalse($updatePanel->getVersionLevelDifference('3.0', '3.1'));
    }

    public function testNewestSilverStripeVersion()
    {
        $updatePanel = UpdatePanel::create();

        $this->assertTrue($updatePanel->isNewestSilverStripeVersion('3.6.1', '3.6.1'));
        $this->assertTrue($updatePanel->isNewestSilverStripeVersion('4.0.0', '3.0.0'));
        $this->assertTrue($updatePanel->isNewestSilverStripeVersion('3.6.0', '3.5.0'));
        $this->assertTrue($updatePanel->isNewestSilverStripeVersion('3.6.1', '3.6.0'));

        $this->assertFalse($updatePanel->isNewestSilverStripeVersion('3.0.0', '4.0.0'));
        $this->assertFalse($updatePanel->isNewestSilverStripeVersion('3.1.0', '3.2.0'));
        $this->assertFalse($updatePanel->isNewestSilverStripeVersion('3.6.0', '3.6.1'));
    }
}
