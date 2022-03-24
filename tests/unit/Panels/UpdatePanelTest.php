<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Admin\DashboardAdmin;
use Plastyk\Dashboard\Panels\UpdatePanel;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Member;

class UpdatePanelTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $fixture_file = '../../fixtures/DashboardAdminTest.yml';

    public function testCreateUpdatePanel()
    {
        $updatePanel = new UpdatePanel();
        $this->assertNotNull($updatePanel);
    }

    public function testCanView()
    {
        $updatePanelCache = Injector::inst()->get(CacheInterface::class . '.plastykDashboardCache');

        $updatePanel = UpdatePanel::singleton();

        $updatePanelCache->set('CurrentSilverstripeVersion', '255.0.0');

        $this->assertFalse($updatePanel->canView());

        $updatePanelCache->set('CurrentSilverstripeVersion', '4.0.0');

        $this->assertTrue($updatePanel->canView());

        $nonPermittedUser = $this->objFromFixture(Member::class, 'user2');
        $this->logInAs($nonPermittedUser);

        $this->assertFalse($updatePanel->canView());
    }

    public function testGetData()
    {
        DashboardAdmin::config()->set('contact_email', 'angela.davis@gmail.com');
        DashboardAdmin::config()->set('contact_name', 'Angela Davis');

        $updatePanel = UpdatePanel::singleton();

        $data = $updatePanel->getData();

        $this->assertTrue(isset($data['ContactEmail']));
        $this->assertEquals('angela.davis@gmail.com', $data['ContactEmail']);
        $this->assertTrue(isset($data['ContactName']));
        $this->assertEquals('Angela Davis', $data['ContactName']);

        DashboardAdmin::config()->remove('contact_email');
        DashboardAdmin::config()->remove('contact_name');

        $data = $updatePanel->getData();

        $this->assertTrue(isset($data['ContactEmail']));
        $this->assertFalse($data['ContactEmail']);
        $this->assertTrue(isset($data['ContactName']));
        $this->assertEquals('your web developer', $data['ContactName']);
    }

    public function testGetContactContent()
    {
        DashboardAdmin::config()->set('contact_email', 'angela.davis@gmail.com');
        DashboardAdmin::config()->set('contact_name', 'Angela Davis');

        $updatePanel = UpdatePanel::singleton();

        $contactContent = $updatePanel->getContactContent();

        $this->assertStringContainsString('mailto:angela.davis@gmail.com', $contactContent->Value);
        $this->assertStringContainsString('Angela Davis', $contactContent->Value);

        DashboardAdmin::config()->remove('contact_email');
        DashboardAdmin::config()->remove('contact_name');

        $contactContent = $updatePanel->getContactContent();

        $this->assertStringContainsString('your web developer', $contactContent->Value);
        $this->assertEquals(
            'If you would like to update to the latest version please contact your web developer.',
            $contactContent->Value
        );
    }

    public function testGetCurrentSilverstripeVersion()
    {
        $updatePanel = UpdatePanel::singleton();

        $currentSilverstripeVersion = $updatePanel->getCurrentSilverstripeVersion();

        $this->assertStringContainsString('.', $currentSilverstripeVersion->FullVersion);
        $this->assertEquals(
            $currentSilverstripeVersion->FullVersion,
            $currentSilverstripeVersion->Major . '.' .
                $currentSilverstripeVersion->Minor . '.' .
                $currentSilverstripeVersion->Patch
        );

        $updatePanelCache = Injector::inst()->get(CacheInterface::class . '.plastykDashboardCache');

        $updatePanelCache->clear('CurrentSilverstripeVersion');

        $currentSilverstripeVersion = $updatePanel->getCurrentSilverstripeVersion();

        $this->assertStringContainsString(
            $currentSilverstripeVersion->Major . '.',
            $currentSilverstripeVersion->FullVersion
        );
    }

    public function testGetLatestSilverstripeVersion()
    {
        $updatePanel = UpdatePanel::singleton();

        $silverStripeVersion = $updatePanel->getLatestSilverstripeVersion();

        $this->assertStringContainsString('.', $silverStripeVersion->FullVersion);
        $this->assertEquals(
            $silverStripeVersion->FullVersion,
            $silverStripeVersion->Major . '.' . $silverStripeVersion->Minor . '.' . $silverStripeVersion->Patch
        );

        UpdatePanel::config()->set('ignore_major_updates', true);

        $silverStripeVersion = $updatePanel->getLatestSilverstripeVersion();

        $this->assertStringContainsString('.', $silverStripeVersion->FullVersion);
        $this->assertEquals(
            $silverStripeVersion->FullVersion,
            $silverStripeVersion->Major . '.' . $silverStripeVersion->Minor . '.' . $silverStripeVersion->Patch
        );
        $this->assertEquals(4, $silverStripeVersion->Major);

        UpdatePanel::config()->set('ignore_major_updates', '2019-01-01');

        $silverStripeVersion = $updatePanel->getLatestSilverstripeVersion();

        $this->assertStringContainsString('.', $silverStripeVersion->FullVersion);
        $this->assertEquals(
            $silverStripeVersion->FullVersion,
            $silverStripeVersion->Major . '.' . $silverStripeVersion->Minor . '.' . $silverStripeVersion->Patch
        );
        $this->assertEquals(4, $silverStripeVersion->Major);

        $updatePanelCache = Injector::inst()->get(CacheInterface::class . '.plastykDashboardCache');

        $updatePanelCache->set('CurrentSilverstripeVersion', '3.0.0');

        $silverStripeVersion = $updatePanel->getLatestSilverstripeVersion();

        $this->assertStringContainsString('.', $silverStripeVersion->FullVersion);
        $this->assertEquals(
            $silverStripeVersion->FullVersion,
            $silverStripeVersion->Major . '.' . $silverStripeVersion->Minor . '.' . $silverStripeVersion->Patch
        );
        $this->assertEquals(4, $silverStripeVersion->Major);

        UpdatePanel::config()->set('ignore_major_updates', '2030-01-01');

        $silverStripeVersion = $updatePanel->getLatestSilverstripeVersion();

        $this->assertStringContainsString('.', $silverStripeVersion->FullVersion);
        $this->assertEquals(
            $silverStripeVersion->FullVersion,
            $silverStripeVersion->Major . '.' . $silverStripeVersion->Minor . '.' . $silverStripeVersion->Patch
        );
        $this->assertEquals(3, $silverStripeVersion->Major);

        UpdatePanel::config()->set('ignore_major_updates', 'xyz');

        $this->expectException(\PHPUnit\Framework\Error\Warning::class);

        $silverStripeVersion = $updatePanel->getLatestSilverstripeVersion();
    }
}
