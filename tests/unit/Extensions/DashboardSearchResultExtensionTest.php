<?php

namespace Plastyk\Dashboard\Tests;

use SilverStripe\Admin\AdminRootController;
use SilverStripe\Dev\SapphireTest;

class DashboardSearchResultExtensionTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testGetSearchResultCMSLink()
    {
        $adminUrl = AdminRootController::admin_url();

        FakeDataObject::config()->set(
            'dashboard_admin_link',
            $adminUrl . '/fake-data-objects/fake-data-object/edit/$ID/'
        );

        $fakeDataObject1 = FakeDataObject::create();
        $fakeDataObject1->write();

        $fakeDataObject2 = FakeDataObject::create();
        $fakeDataObject2->write();

        $this->assertEquals(
            $adminUrl . '/fake-data-objects/fake-data-object/edit/' . $fakeDataObject1->ID . '/',
            $fakeDataObject1->getSearchResultCMSLink()
        );

        $this->assertEquals(
            $adminUrl . '/fake-data-objects/fake-data-object/edit/' . $fakeDataObject2->ID . '/',
            $fakeDataObject2->getSearchResultCMSLink()
        );

        FakeDataObject::config()->remove('dashboard_admin_link');

        $this->assertEquals('', $fakeDataObject1->getSearchResultCMSLink());
    }
}
