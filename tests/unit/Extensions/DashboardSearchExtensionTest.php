<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use SilverStripe\Dev\FunctionalTest;

class DashboardSearchExtensionTest extends FunctionalTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testDoDashboardSearch()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $this->logInWithPermission('ADMIN');

        $dashboardPage = $this->get('admin/dashboard/');

        $this->assertEquals(200, $dashboardPage->getStatusCode());

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=women');

        $this->assertEquals(200, $dashboardPage->getStatusCode());

        $this->assertContains('Sorry, no results found.', $dashboardPage->getBody());
        $this->assertContains('Search Results for <em>\'women\'</em>', $dashboardPage->getBody());
        $this->assertContains('Searching for Pages, Members &amp; Files', $dashboardPage->getBody());

        $page1 = \Page::create([
            'Title' => 'rights of women',
        ]);
        $page1->write();

        $this->assertTrue($page1->canView());

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=women');

        $this->assertEquals(200, $dashboardPage->getStatusCode());

        $this->assertNotContains('Sorry, no results found.', $dashboardPage->getBody());
        $this->assertNotContains('\/admin\/pages\/edit\/show\/' . $page1->ID, $dashboardPage->getBody());
    }
}
