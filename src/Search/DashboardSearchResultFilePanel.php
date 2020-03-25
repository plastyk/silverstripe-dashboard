<?php

namespace Plastyk\Dashboard\Search;

use Plastyk\Dashboard\Model\DashboardSearchResultPanel;
use SilverStripe\AssetAdmin\Controller\AssetAdmin;
use SilverStripe\Assets\File;
use SilverStripe\Security\Permission;

class DashboardSearchResultFilePanel extends DashboardSearchResultPanel
{
    protected $className = File::class;
    protected $searchFields = ['Title', 'Name', 'FileFilename'];
    protected $sort = ['Title' => 'ASC'];
    protected $exclusions = ['ClassName' => 'Folder'];

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_AssetAdmin')
            && class_exists(AssetAdmin::class)
            && parent::canView($member);
    }
}
