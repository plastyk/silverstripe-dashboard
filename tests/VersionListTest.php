<?php

class VersionListTest extends FunctionalTest
{
    public function testHasNewerVersion()
    {
        $versionList = VersionList::create(array('4.0.0'));
        $this->assertTrue($versionList->hasNewerVersion('3.0.0'));
        $versionList = VersionList::create(array('3.6.0'));
        $this->assertTrue($versionList->hasNewerVersion('3.5.0'));
        $versionList = VersionList::create(array('3.6.1'));
        $this->assertTrue($versionList->hasNewerVersion('3.6.0'));

        $versionList = VersionList::create(array('3.6.1'));
        $this->assertFalse($versionList->hasNewerVersion('3.6.1'));

        $versionList = VersionList::create(array('3.6.1'));
        $this->assertFalse($versionList->hasNewerVersion('3.6.1'));
        $versionList = VersionList::create(array('3.0.0'));
        $this->assertFalse($versionList->hasNewerVersion('4.0.0'));
        $versionList = VersionList::create(array('3.1.0'));
        $this->assertFalse($versionList->hasNewerVersion('3.2.0'));
        $versionList = VersionList::create(array('3.6.0'));
        $this->assertFalse($versionList->hasNewerVersion('3.6.1'));
    }
}
