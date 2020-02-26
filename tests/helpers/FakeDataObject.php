<?php

namespace Plastyk\Dashboard\Tests;

use Plastyk\Dashboard\Extensions\DashboardSearchResultExtension;
use SilverStripe\ORM\DataObject;

class FakeDataObject extends DataObject
{
    private static $extensions = [
        DashboardSearchResultExtension::class,
    ];

    private static $singular_name = 'Fake';

    public function plural_name()
    {
        return 'Fakes';
    }
}
