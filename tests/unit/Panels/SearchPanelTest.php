<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Panels\SearchPanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class SearchPanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateSearchPanel()
    {
        $searchPanel = new SearchPanel();
        $this->assertNotNull($searchPanel);
    }

    public function testCanView()
    {
        $searchPanel = SearchPanel::singleton();

        $this->assertTrue($searchPanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($searchPanel->canView());
    }

    public function testGetData()
    {
        //$searchPanel = new SearchPanel();

        //$data = $searchPanel->getData();

        //$this->assertTrue(isset($data['SearchValue']));
        //$this->assertFalse($data['SearchValue']);
    }
}
