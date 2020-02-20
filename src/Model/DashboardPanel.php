<?php

namespace Plastyk\Dashboard\Model;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Security\Permission;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

abstract class DashboardPanel
{
    use Extensible;
    use Injectable;
    use Configurable;

    protected $controller;

    public function __construct($controller = null)
    {
        $this->controller = $controller;
        $this->init();
    }

    public function init()
    {
    }

    public function getData()
    {
        return [];
    }

    public function forTemplate()
    {
        if (!$this->canView()) {
            return false;
        }

        $class = get_class($this);
        $ancestry = ClassInfo::ancestry($class);
        array_reverse($ancestry);
        $template = new SSViewer($ancestry);

        $data = $this->getData();

        return $template->process(new ArrayData([]), $data);
    }

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_DASHBOARDADMIN');
    }
}
