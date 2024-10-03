<?php

namespace Plastyk\Dashboard\Panels;

use Plastyk\Dashboard\Model\DashboardPanel;
use Plastyk\Dashboard\Model\QuickLink;
use ReflectionClass;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Security\Security;
use SilverStripe\View\Requirements;

class QuickLinksPanel extends DashboardPanel
{
    private static $columns = 12;

    private static $section = 'top';

    public function canView($member = null)
    {
        $quickLinks = $this->getQuickLinks();

        return count($quickLinks) > 0 && parent::canView($member);
    }

    public function init()
    {
        parent::init();
        Requirements::css('plastyk/dashboard:css/dashboard-quick-links-panel.css');
    }

    private function getQuickLinks()
    {
        $member = Security::getCurrentUser();

        $quickLinks = ClassInfo::subclassesFor(QuickLink::class);

        $quickLinkItems = [];

        if ($quickLinks && count($quickLinks ?? []) > 0) {
            $section = $this->getSection();

            foreach ($quickLinks as $quickLink) {
                $reflectionClass = new ReflectionClass($quickLink);

                if ($reflectionClass->isAbstract()) {
                    continue;
                }

                $quickLinkObject = $quickLink::create();

                if (
                    ! $quickLinkObject->canView($member) ||
                    ! $quickLinkObject->getEnabled()
                ) {
                    continue;
                }
                
                $quickLinkItems[$quickLink] = $quickLinkObject;
            }
        }

        uasort($quickLinkItems, function ($a, $b) {
            if ($a->getSort() == $b->getSort()) {
                return 0;
            } else {
                return ($a->getSort() < $b->getSort()) ? -1 : 1;
            }
        });

        return $quickLinkItems;
    }
}
