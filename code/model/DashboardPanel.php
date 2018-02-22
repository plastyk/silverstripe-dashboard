<?php

abstract class DashboardPanel extends Object
{
    protected $controller;

    public function __construct($controller = null)
    {
        parent::__construct();
        $this->controller = $controller;
        $this->init();
    }

    public function init()
    {
    }

    public function getData()
    {
        return array();
    }

    public function forTemplate()
    {
        if (!$this->canView()) {
            return false;
        }

        $class = get_class($this);
        $ancestry = ClassInfo::ancestry($class);
        $ancestry = array_slice($ancestry, 2);
        array_reverse($ancestry);
        $template = new SSViewer($ancestry);

        $data = $this->getData();

        return $template->process(new ArrayData(array()), $data);
    }

    public function canView($member = null)
    {
        return Permission::checkMember($member, 'CMS_ACCESS_DASHBOARDADMIN');
    }
}
