<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use SilverStripe\Dev\FunctionalTest;

class DashboardAdminTest extends FunctionalTest
{
    protected static $fixture_file = 'dashboard/tests/DashboardAdminTest.yml';

    public function testPermission()
    {
        $dashboardAdmin = DashboardAdmin::create();

        $permittedUser = $this->objFromFixture('Member', 'user1');
        $this->logInAs($permittedUser);
        $this->assertTrue($dashboardAdmin->canView());

        $nonPermittedUser = $this->objFromFixture('Member', 'user2');
        $this->logInAs($nonPermittedUser);
        $this->assertFalse($dashboardAdmin->canView());
    }
}
