<?php

namespace Plastyk\Dashboard\Model;

use ReflectionClass;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\FieldType\DBField;

abstract class DashboardPanelSection
{
    use Extensible;
    use Injectable;
    use Configurable;

    /**
     * @var int $sort The sort order of this dashboard panel section
     */
    protected $sort = 50;

    /**
     * @var bool $enabled If set to FALSE, this dashboard panel section will not display
     */
    protected $enabled = true;

    /**
     * @var int $section The section of this dashboard panel
     */
    protected $section;

    public function forTemplate()
    {
        $dashboardPanels = $this->getSectionDashboardPanels();

        $content = '';

        foreach ($dashboardPanels as $dashboardPanel) {
            $content .= $dashboardPanel->forTemplate();
        }
        
        return DBField::create_field('HTMLText', $content);
    }

    private function getSectionDashboardPanels()
    {
        $dashboardPanels = ClassInfo::subclassesFor(DashboardPanel::class);

        $sectionDashboardPanels = [];

        if ($dashboardPanels && count($dashboardPanels ?? []) > 0) {
            foreach ($dashboardPanels as $dashboardPanel) {
                $reflectionClass = new ReflectionClass($dashboardPanel);

                if ($reflectionClass->isAbstract()) {
                    continue;
                }

                $dashboardPanelObject = $dashboardPanel::create();

                if (
                    $dashboardPanelObject->getSection() !== $this->section ||
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

    public function getSort()
    {
        return $this->sort;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }
}
