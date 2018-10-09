<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\Member;

class DashboardAdminTest extends FunctionalTest
{
    protected static $fixture_file = 'DashboardAdminTest.yml';

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
