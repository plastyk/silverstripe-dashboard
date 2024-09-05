<?php

namespace Plastyk\Dashboard\Model;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
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

    /**
     * @var int $sort The sort order of this dashboard panel
     */
    private static $sort = 0;

    /**
     * @var bool $enabled If set to FALSE, this dashboard panel will not display
     */
    private static $enabled = true;

    /**
     * @var int $section The section of this dashboard panel
     */
    private static $section = 'main';

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

    public function getEnabled()
    {
        return Config::inst()->get($this::class, 'enabled');
    }

    public function getSection()
    {
        return Config::inst()->get($this::class, 'section');
    }

    public function getSort()
    {
        return Config::inst()->get($this::class, 'sort');
    }
}
