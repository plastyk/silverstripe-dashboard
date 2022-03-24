<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use Plastyk\Dashboard\Panels\MoreInformationPanel;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class MoreInformationPanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateMoreInformationPanel()
    {
        $moreInformationPanel = new MoreInformationPanel();
        $this->assertNotNull($moreInformationPanel);
    }

    public function testCanView()
    {
        $moreInformationPanel = MoreInformationPanel::singleton();

        $this->assertTrue($moreInformationPanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($moreInformationPanel->canView());
    }

    public function testGetData()
    {
        DashboardAdmin::config()->set('contact_email', 'roxane.gay@gmail.com');
        DashboardAdmin::config()->set('contact_name', 'Roxane Gay');

        $moreInformationPanel = MoreInformationPanel::singleton();

        $data = $moreInformationPanel->getData();

        $this->assertTrue(isset($data['ContactEmail']));
        $this->assertEquals('roxane.gay@gmail.com', $data['ContactEmail']);
        $this->assertTrue(isset($data['ContactName']));
        $this->assertEquals('Roxane Gay', $data['ContactName']);

        DashboardAdmin::config()->remove('contact_email');
        DashboardAdmin::config()->remove('contact_name');

        $data = $moreInformationPanel->getData();

        $this->assertTrue(isset($data['ContactEmail']));
        $this->assertFalse($data['ContactEmail']);
        $this->assertTrue(isset($data['ContactName']));
        $this->assertEquals('your web developer', $data['ContactName']);
    }

    public function testGetContent()
    {
        DashboardAdmin::config()->set('contact_email', 'roxane.gay@gmail.com');
        DashboardAdmin::config()->set('contact_name', 'Roxane Gay');

        $moreInformationPanel = MoreInformationPanel::singleton();

        $content = $moreInformationPanel->getContent();

        $this->assertStringContainsString('Custom dashboard panels are available.', $content->value);
        $this->assertStringContainsString('mailto:roxane.gay@gmail.com', $content->value);

        DashboardAdmin::config()->remove('contact_email');
        DashboardAdmin::config()->remove('contact_name');

        $content = $moreInformationPanel->getContent();

        $this->assertStringContainsString(
            'Contact your web developer if you would like to discuss.',
            $content->value
        );
    }
}
