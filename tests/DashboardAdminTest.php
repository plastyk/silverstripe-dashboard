<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class DashboardAdminTest extends SapphireTest
{
    protected static $fixture_file = 'DashboardAdminTest.yml';

    protected function setUp()
    {
        DashboardAdmin::create();

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testPermission()
    {
        $dashboardAdmin = DashboardAdmin::create();

        $permittedUser = $this->objFromFixture(Member::class, 'user1');
        $this->logInAs($permittedUser);
        $this->assertTrue($dashboardAdmin->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);
        $this->assertFalse($dashboardAdmin->canView());
    }
}
