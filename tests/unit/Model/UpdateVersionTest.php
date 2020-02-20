<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Model\UpdateVersion;
use SilverStripe\Dev\SapphireTest;

class UpdateVersionTest extends SapphireTest
{
    public function testGetVersionDifference()
    {
        $this->assertEquals('major', UpdateVersion::getVersionDifference('3.0.0', '4.0.0'));
        $this->assertEquals('major', UpdateVersion::getVersionDifference('3.1.0', '4.5.0'));
        $this->assertEquals('major', UpdateVersion::getVersionDifference('3.6.1', '4.0.0'));
        $this->assertEquals('minor', UpdateVersion::getVersionDifference('3.0.0', '3.1.0'));
        $this->assertEquals('minor', UpdateVersion::getVersionDifference('3.1.0', '3.5.0'));
        $this->assertEquals('minor', UpdateVersion::getVersionDifference('3.5.1', '3.6.0'));
        $this->assertEquals('patch', UpdateVersion::getVersionDifference('3.0.0', '3.0.1'));
        $this->assertEquals('patch', UpdateVersion::getVersionDifference('3.1.2', '3.1.5'));

        $this->assertFalse(UpdateVersion::getVersionDifference('3.6.0', '3.6.0'));
        $this->assertFalse(UpdateVersion::getVersionDifference('4.0.0', '3.6.0'));

        $currentVersion = UpdateVersion::fromVersionString('4.5.1');
        $newVersion = UpdateVersion::fromVersionString('4.5.1');

        $currentVersion->VersionCode = $newVersion->VersionCode - 1;
        $this->assertFalse(UpdateVersion::getVersionDifference($currentVersion, $newVersion));
    }

    public function testFromVersionString()
    {
        $version = UpdateVersion::fromVersionString('0.1.0');
        $this->assertEquals(0, $version->Major);
        $this->assertEquals(1, $version->Minor);
        $this->assertEquals(0, $version->Patch);
        $this->assertTrue($version->PreRelease);

        $version = UpdateVersion::fromVersionString('4.0.0');
        $this->assertEquals(4, $version->Major);
        $this->assertEquals(0, $version->Minor);
        $this->assertEquals(0, $version->Patch);
        $this->assertFalse($version->PreRelease);

        $version = UpdateVersion::fromVersionString('3.6.1');
        $this->assertEquals(3, $version->Major);
        $this->assertEquals(6, $version->Minor);
        $this->assertEquals(1, $version->Patch);
        $this->assertFalse($version->PreRelease);

        $version = UpdateVersion::fromVersionString('4.x-dev');
        $this->assertEquals(4, $version->Major);
        $this->assertEquals(255, $version->Minor);
        $this->assertEquals(255, $version->Patch);
        $this->assertTrue($version->PreRelease);

        $version = UpdateVersion::fromVersionString('4.0.0-alpha1');
        $this->assertEquals(4, $version->Major);
        $this->assertEquals(0, $version->Minor);
        $this->assertEquals(0, $version->Patch);
        $this->assertTrue($version->PreRelease);
        $this->assertEquals('alpha1', $version->PreReleaseType);

        $version = UpdateVersion::fromVersionString('4.0.0-beta+2017.07.25');
        $this->assertEquals(4, $version->Major);
        $this->assertEquals(0, $version->Minor);
        $this->assertEquals(0, $version->Patch);
        $this->assertTrue($version->PreRelease);
        $this->assertEquals('beta', $version->PreReleaseType);
        $this->assertEquals('2017.07.25', $version->Build);

        $version = UpdateVersion::fromVersionString('dev-master');
        $this->assertEquals(255, $version->Major);
        $this->assertEquals(255, $version->Minor);
        $this->assertEquals(255, $version->Patch);
        $this->assertTrue($version->PreRelease);
        $this->assertEquals('dev-master', $version->PreReleaseType);
    }
}
