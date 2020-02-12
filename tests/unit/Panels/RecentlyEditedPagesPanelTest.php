<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Panels\RecentlyEditedPagesPanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class RecentlyEditedPagesPanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateRecentlyEditedPagesPanel()
    {
        $recentlyEditedPagesPanel = new RecentlyEditedPagesPanel();
        $this->assertNotNull($recentlyEditedPagesPanel);
    }

    public function testCanView()
    {
        $recentlyEditedPagesPanel = RecentlyEditedPagesPanel::singleton();

        $this->assertTrue($recentlyEditedPagesPanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($recentlyEditedPagesPanel->canView());
    }

    public function testGetData()
    {
        $recentlyEditedPagesPanel = RecentlyEditedPagesPanel::singleton();

        $data = $recentlyEditedPagesPanel->getData();

        $this->assertTrue(isset($data['Results']));
        $this->assertEquals(0, $data['Results']->count());
    }

    public function testGetResults()
    {
        $page1 = \Page::create();
        $page1->write();
        $page2 = \Page::create();
        $page2->write();

        $recentlyEditedPagesPanel = RecentlyEditedPagesPanel::singleton();

        $results = $recentlyEditedPagesPanel->getResults();

        $this->assertEquals(2, $results->count());
    }
}
