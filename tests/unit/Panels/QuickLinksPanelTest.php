<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Panels\QuickLinksPanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class QuickLinksPanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateQuickLinksPanel()
    {
        $quickLinksPanel = new QuickLinksPanel();
        $this->assertNotNull($quickLinksPanel);
    }

    public function testCanView()
    {
        $quickLinksPanel = QuickLinksPanel::singleton();

        $this->assertTrue($quickLinksPanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($quickLinksPanel->canView());
    }

    public function testGetData()
    {
        $quickLinksPanel = QuickLinksPanel::singleton();

        $data = $quickLinksPanel->getData();

        $this->assertTrue(isset($data['CanView']));
        $this->assertTrue($data['CanView']);
        $this->assertTrue(isset($data['CanViewPages']));
        $this->assertTrue($data['CanViewPages']);

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $data = $quickLinksPanel->getData();

        $this->assertTrue(isset($data['CanView']));
        $this->assertFalse($data['CanView']);
        $this->assertTrue(isset($data['CanViewPages']));
        $this->assertFalse($data['CanViewPages']);
    }
}
