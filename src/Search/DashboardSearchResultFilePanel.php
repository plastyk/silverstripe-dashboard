<?php

namespace Plastyk\Dashboard\Search;

use Plastyk\Dashboard\Model\DashboardSearchResultPanel;
use SilverStripe\Security\Permission;
use SilverStripe\Assets\File;

class DashboardSearchResultFilePanel extends DashboardSearchResultPanel
{
    protected $className = 'SilverStripe\Assets\File';
    protected $searchFields = array('Title', 'Name', 'FileFilename');
    protected $sort = array('Title' => 'ASC');
    protected $exclusions = array('ClassName' => 'Folder');

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_AssetAdmin') && class_exists('SilverStripe\AssetAdmin\Controller\AssetAdmin') && parent::canView($member);
    }
}
