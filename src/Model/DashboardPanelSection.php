<?php

namespace Plastyk\Dashboard\Model;

use ReflectionClass;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

abstract class DashboardPanelSection
{
    use Extensible;
    use Injectable;
    use Configurable;

    /**
     * @var int $sort The sort order of this dashboard panel section
     */
    private static $sort = 50;

    /**
     * @var bool $enabled If set to FALSE, this dashboard panel section will not display
     */
    private static $enabled = true;

    /**
     * @var int $section The section of this dashboard panel
     */
    private static $section;

    public function forTemplate()
    {
        $dashboardPanels = $this->getSectionDashboardPanels();

        $sectionContent = '';

        foreach ($dashboardPanels as $dashboardPanel) {
            $sectionContent .= $dashboardPanel->forTemplate();
        }

        $template = SSViewer::create('Plastyk/Dashboard/Includes/DashboardPanelSection');

        return $template->process(new ArrayData([
            'SectionContent' => DBField::create_field('HTMLText', $sectionContent),
        ]));
    }

    private function getSectionDashboardPanels()
    {
        $dashboardPanels = ClassInfo::subclassesFor(DashboardPanel::class);

        $sectionDashboardPanels = [];

        if ($dashboardPanels && count($dashboardPanels ?? []) > 0) {
            $section = $this->getSection();

            foreach ($dashboardPanels as $dashboardPanel) {
                $reflectionClass = new ReflectionClass($dashboardPanel);

                if ($reflectionClass->isAbstract()) {
                    continue;
                }

                $dashboardPanelObject = $dashboardPanel::create();

                if (
                    $dashboardPanelObject->getSection() !== $section ||
                    ! $dashboardPanelObject->canView() ||
                    ! $dashboardPanelObject->getEnabled()
                ) {
                    continue;
                }
                
                $sectionDashboardPanels[$dashboardPanel] = $dashboardPanelObject;
            }
        }

        uasort($sectionDashboardPanels, function ($a, $b) {
            if ($a->getSort() == $b->getSort()) {
                return 0;
            } else {
                return ($a->getSort() < $b->getSort()) ? -1 : 1;
            }
        });

        return $sectionDashboardPanels;
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
