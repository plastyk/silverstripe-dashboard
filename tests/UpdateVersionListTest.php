<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Model\UpdateVersionList;
use SilverStripe\Dev\FunctionalTest;

class UpdateVersionListTest extends FunctionalTest
{
    public function testHasNewerVersion()
    {
        $versionList = UpdateVersionList::create(array('4.0.0'));
        $this->assertTrue($versionList->hasNewerVersion('3.0.0'));
        $versionList = UpdateVersionList::create(array('3.6.0'));
        $this->assertTrue($versionList->hasNewerVersion('3.5.0'));
        $versionList = UpdateVersionList::create(array('3.6.1'));
        $this->assertTrue($versionList->hasNewerVersion('3.6.0'));

        $versionList = UpdateVersionList::create(array('3.6.1'));
        $this->assertFalse($versionList->hasNewerVersion('3.6.1'));

        $versionList = UpdateVersionList::create(array('3.6.1'));
        $this->assertFalse($versionList->hasNewerVersion('3.6.1'));
        $versionList = UpdateVersionList::create(array('3.0.0'));
        $this->assertFalse($versionList->hasNewerVersion('4.0.0'));
        $versionList = UpdateVersionList::create(array('3.1.0'));
        $this->assertFalse($versionList->hasNewerVersion('3.2.0'));
        $versionList = UpdateVersionList::create(array('3.6.0'));
        $this->assertFalse($versionList->hasNewerVersion('3.6.1'));
    }
}
