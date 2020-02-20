<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Search\DashboardSearchResultPagePanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class DashboardSearchResultPanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateDashboardSearchResultPagePanel()
    {
        $dashboardSearchResultPagePanel = new DashboardSearchResultPagePanel();
        $this->assertNotNull($dashboardSearchResultPagePanel);
    }

    public function testCanView()
    {
        $dashboardSearchResultPagePanel = DashboardSearchResultPagePanel::singleton();

        $this->assertTrue($dashboardSearchResultPagePanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($dashboardSearchResultPagePanel->canView());
    }

    public function testGetClassName()
    {
        $dashboardSearchResultPagePanel = DashboardSearchResultPagePanel::singleton();

        $this->assertEquals(\Page::class, $dashboardSearchResultPagePanel->getClassName());
    }

    public function testGetSingularName()
    {
        $dashboardSearchResultPagePanel = DashboardSearchResultPagePanel::singleton();

        $this->assertEquals('Page', $dashboardSearchResultPagePanel->getSingularName());
    }

    public function testGetPluralName()
    {
        $dashboardSearchResultPagePanel = DashboardSearchResultPagePanel::singleton();

        $this->assertEquals('Pages', $dashboardSearchResultPagePanel->getPluralName());
    }

    public function testForTemplate()
    {
        $page = \Page::create(['Title' => 'Feminism']);
        $page->write();

        $dashboardSearchResultPagePanel = new DashboardSearchResultPagePanel();

        $content = $dashboardSearchResultPagePanel->forTemplate()->value;

        $this->assertNotContains('DashboardSearchResultPagePanel', $content);
        $this->assertNotContains('Feminism', $content);

        $searchResults = $dashboardSearchResultPagePanel->performSearch('Fem');

        $content = $dashboardSearchResultPagePanel->forTemplate()->value;

        $this->assertContains('Feminism', $content);
        $this->assertContains('DashboardSearchResultPagePanel', $content);

        $searchResults = $dashboardSearchResultPagePanel->performSearch('Patriarchy');

        $content = $dashboardSearchResultPagePanel->forTemplate()->value;

        $this->assertNotContains('DashboardSearchResultPagePanel', $content);
        $this->assertNotContains('Feminism', $content);
    }

    public function testGetResults()
    {
        $page = \Page::create(['Title' => 'Feminism']);
        $page->write();

        $dashboardSearchResultPagePanel = new DashboardSearchResultPagePanel();

        $results = $dashboardSearchResultPagePanel->getResults();

        $this->assertEquals(0, $results->count());

        $searchResults = $dashboardSearchResultPagePanel->performSearch('Fem');

        $results = $dashboardSearchResultPagePanel->getResults();

        $this->assertEquals(1, $results->count());

        $searchResults = $dashboardSearchResultPagePanel->performSearch('Patriarchy');

        $results = $dashboardSearchResultPagePanel->getResults();

        $this->assertEquals(0, $results->count());
    }

    public function testPerformSearch()
    {
        $page = \Page::create(['Title' => 'Feminism']);
        $page->write();

        $dashboardSearchResultPagePanel = new DashboardSearchResultPagePanel();

        $searchResults = $dashboardSearchResultPagePanel->performSearch('Fem');

        $this->assertEquals(1, $searchResults->count());

        $searchResults = $dashboardSearchResultPagePanel->performSearch('Fem ism');

        $this->assertEquals(1, $searchResults->count());

        $searchResults = $dashboardSearchResultPagePanel->performSearch('Patriarchy');

        $this->assertEquals(0, $searchResults->count());
    }
}
