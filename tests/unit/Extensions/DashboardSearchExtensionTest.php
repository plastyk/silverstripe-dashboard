<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use Plastyk\Dashboard\Search\DashboardSearchResultPagePanel;
use SilverStripe\Assets\File;
use SilverStripe\CMS\Controllers\CMSPageEditController;
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

        $this->assertStringContainsString('Sorry, no results found.', $dashboardPage->getBody());
        $this->assertStringContainsString('Search Results for <em>\'women\'</em>', $dashboardPage->getBody());
        $this->assertStringContainsString('Searching for Pages, Members &amp; Files', $dashboardPage->getBody());

        $page1 = \Page::create([
            'Title' => 'Women\s rights',
        ]);
        $page1->write();

        $this->assertTrue($page1->canView());

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=women');

        $this->assertStringContainsString('dashboardadmin-cms-content', $dashboardPage->getBody());
        $this->assertStringNotContainsString('Sorry, no results found.', $dashboardPage->getBody());
        $this->assertStringNotContainsString('\/admin\/pages\/edit\/show\/' . $page1->ID, $dashboardPage->getBody());
    }

    public function testDoDashboardSearchAjax()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $this->logInWithPermission('ADMIN');

        $page1 = \Page::create([
            'Title' => 'Women\s rights',
        ]);
        $page1->write();

        $this->assertTrue($page1->canView());

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=women&ajax=1');

        $this->assertStringContainsString('dashboardadmin-cms-content', $dashboardPage->getBody());
        $this->assertStringNotContainsString('<body', $dashboardPage->getBody());
    }

    public function testDoDashboardBlankSearchValueRedirect()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $this->logInWithPermission('ADMIN');

        $page1 = \Page::create([
            'Title' => 'Women\s rights',
        ]);
        $page1->write();

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=');

        $this->assertStringNotContainsString('Search Results', $dashboardPage->getBody());
        $this->assertStringContainsString('<h1>Your Site Name</h1>', $dashboardPage->getBody());

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=&ajax=1');

        $this->assertStringNotContainsString('Search Results', $dashboardPage->getBody());
        $this->assertStringContainsString('<h1>Your Site Name</h1>', $dashboardPage->getBody());
    }

    public function testDoDashboardSearchSinglePanel()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $this->logInWithPermission('ADMIN');

        $page1 = \Page::create([
            'Title' => 'Women\s rights',
        ]);
        $page1->write();

        DashboardAdmin::config()->set('search_panels', [DashboardSearchResultPagePanel::class]);

        $dashboardPage = $this->get(
            'admin/dashboard/DashboardSearchForm/?Search=women&panel-class=' .
            DashboardSearchResultPagePanel::class
        );

        $this->assertStringContainsString(
            'Plastyk-Dashboard-Search-DashboardSearchResultPagePanel',
            $dashboardPage->getBody()
        );
    }

    public function testDashboardAutomaticSearchRedirect()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $this->logInWithPermission('ADMIN');

        $page1 = \Page::create([
            'Title' => 'Women\s rights',
        ]);
        $page1->write();

        \Page::config()->dashboard_automatic_search_redirect = true;

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=women');

        $this->assertStringNotContainsString(
            'Plastyk-Dashboard-Search-DashboardSearchResultPagePanel',
            $dashboardPage->getBody()
        );

        $this->assertEquals('Silverstripe+-+Edit+Page', $dashboardPage->getHeader('x-title'));
        $this->assertEquals(CMSPageEditController::class, $dashboardPage->getHeader('x-controller'));

        $page2 = \Page::create([
            'Title' => 'Inspirational women throughout history',
        ]);
        $page2->write();

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=women');

        $this->assertEquals('Silverstripe+-+Dashboard', $dashboardPage->getHeader('x-title'));
        $this->assertEquals(DashboardAdmin::class, $dashboardPage->getHeader('x-controller'));

        $page2->Title = 'Climate Crisis';
        $page2->write();

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=women');

        $this->assertEquals('Silverstripe+-+Edit+Page', $dashboardPage->getHeader('x-title'));
        $this->assertEquals(CMSPageEditController::class, $dashboardPage->getHeader('x-controller'));

        $file1 = File::create([
            'Title' => 'Women and men',
            'Name' => 'Women and men',
        ]);
        $file1->write();

        $dashboardPage = $this->get('admin/dashboard/DashboardSearchForm/?Search=women');

        $this->assertEquals('Silverstripe+-+Dashboard', $dashboardPage->getHeader('x-title'));
        $this->assertEquals(DashboardAdmin::class, $dashboardPage->getHeader('x-controller'));
    }

    public function testDoPanelSearch()
    {
        $dashboardAdmin = DashboardAdmin::singleton();

        $this->logInWithPermission('ADMIN');

        $page1 = \Page::create([
            'Title' => 'Women\s rights',
        ]);
        $page1->write();

        $dashboardPage = $this->get(
            'admin/dashboard/DashboardSearchForm/?Search=women&ajax=1&panel-class=' .
            DashboardSearchResultPagePanel::class
        );

        $this->assertStringContainsString(
            'Plastyk-Dashboard-Search-DashboardSearchResultPagePanel',
            $dashboardPage->getBody()
        );
        $this->assertStringNotContainsString('<body', $dashboardPage->getBody());

        $dashboardPage = $this->get(
            'admin/dashboard/DashboardSearchForm/?Search=women&ajax=1&panel-class=NonExistantClass'
        );

        $this->assertFalse($dashboardPage->getBody());

        $this->logInWithPermission('CMS_ACCESS_DASHBOARDADMIN');

        $dashboardPage = $this->get(
            'admin/dashboard/DashboardSearchForm/?Search=women&ajax=1&panel-class=' .
            DashboardSearchResultPagePanel::class
        );

        $this->assertFalse($dashboardPage->getBody());
    }
}
