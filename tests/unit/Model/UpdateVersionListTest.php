<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Model\UpdateVersionList;
use SilverStripe\Dev\SapphireTest;

class UpdateVersionListTest extends SapphireTest
{
    public function testFilterMajorReleases()
    {
        $versionList = UpdateVersionList::create([
            '3.0.0',
            '3.6.0',
            '4.0.0',
            '4.0.1',
            '4.5.1',
        ]);

        $this->assertEquals(5, $versionList->count());

        $versionList = $versionList->filterMajorReleases();

        $this->assertEquals(2, $versionList->count());
    }

    public function testHasNewerVersion()
    {
        $versionList = UpdateVersionList::create(['4.0.0']);
        $this->assertTrue($versionList->hasNewerVersion('3.0.0'));
        $versionList = UpdateVersionList::create(['3.6.0']);
        $this->assertTrue($versionList->hasNewerVersion('3.5.0'));
        $versionList = UpdateVersionList::create(['3.6.1']);
        $this->assertTrue($versionList->hasNewerVersion('3.6.0'));

        $versionList = UpdateVersionList::create(['3.6.1']);
        $this->assertFalse($versionList->hasNewerVersion('3.6.1'));

        $versionList = UpdateVersionList::create(['3.6.1']);
        $this->assertFalse($versionList->hasNewerVersion('3.6.1'));
        $versionList = UpdateVersionList::create(['3.0.0']);
        $this->assertFalse($versionList->hasNewerVersion('4.0.0'));
        $versionList = UpdateVersionList::create(['3.1.0']);
        $this->assertFalse($versionList->hasNewerVersion('3.2.0'));
        $versionList = UpdateVersionList::create(['3.6.0']);
        $this->assertFalse($versionList->hasNewerVersion('3.6.1'));
    }
}
