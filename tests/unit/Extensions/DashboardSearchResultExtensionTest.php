<?php

namespace Plastyk\Dashboard\Tests;

use SilverStripe\Dev\SapphireTest;

class DashboardSearchResultExtensionTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testDoDashboardSearch()
    {
        FakeDataObject::config()->set(
            'dashboard_admin_link',
            'admin/fake-data-objects/fake-data-object/edit/$ID/'
        );

        $fakeDataObject1 = FakeDataObject::create();
        $fakeDataObject1->write();
    }
}
