<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use SilverStripe\Dev\FunctionalTest;

class DashboardAdminFunctionalTest extends FunctionalTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testInit()
    {
        DashboardAdmin::config()->remove('panel_accent_color');

        $dashboardAdmin = DashboardAdmin::singleton();

        $this->logInWithPermission('ADMIN');

        $dashboardPage = $this->get('admin/dashboard/');

        $this->assertEquals(200, $dashboardPage->getStatusCode());

        $this->assertContains('<h1>Your Site Name</h1>', $dashboardPage->getBody());
        $this->assertContains('css/dashboard.css', $dashboardPage->getBody());
        $this->assertNotContains('border-top-color: #fff000;', $dashboardPage->getBody());

        DashboardAdmin::config()->set('panel_accent_color', '#fff000');

        $dashboardPage = $this->get('admin/dashboard/');

        $this->assertEquals(200, $dashboardPage->getStatusCode());

        $this->assertContains('border-top-color: #fff000;', $dashboardPage->getBody());
    }
}
