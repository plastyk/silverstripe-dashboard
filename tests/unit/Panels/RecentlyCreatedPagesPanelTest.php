<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Panels\RecentlyCreatedPagesPanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class RecentlyCreatedPagesPanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateRecentlyCreatedPagesPanel()
    {
        $recentlyCreatedPagesPanel = new RecentlyCreatedPagesPanel();
        $this->assertNotNull($recentlyCreatedPagesPanel);
    }

    public function testCanView()
    {
        $recentlyCreatedPagesPanel = RecentlyCreatedPagesPanel::singleton();

        $this->assertTrue($recentlyCreatedPagesPanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($recentlyCreatedPagesPanel->canView());
    }

    public function testGetData()
    {
        $recentlyCreatedPagesPanel = RecentlyCreatedPagesPanel::singleton();

        $data = $recentlyCreatedPagesPanel->getData();

        $this->assertTrue(isset($data['Results']));
        $this->assertEquals(0, $data['Results']->count());
    }

    public function testGetResults()
    {
        $page1 = \Page::create();
        $page1->write();
        $page2 = \Page::create();
        $page2->write();

        $recentlyCreatedPagesPanel = RecentlyCreatedPagesPanel::singleton();

        $results = $recentlyCreatedPagesPanel->getResults();

        $this->assertEquals(2, $results->count());

        $page1->Created = strtotime('-8 months');
        $page1->write();

        $results = $recentlyCreatedPagesPanel->getResults();

        $this->assertEquals(1, $results->count());

        $page2->Created = strtotime('-1 year');
        $page2->write();

        $results = $recentlyCreatedPagesPanel->getResults();

        $this->assertEquals(0, $results->count());
    }
}
