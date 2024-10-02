<?php

namespace Plastyk\Dashboard\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

class DashboardSiteTreeExtension extends DataExtension
{
    public function DashboardBreadcrumbs($maxDepth = 20, $stopAtPageType = false, $showHidden = false, $delimiter = '/')
    {
        $pages = $this->owner->getBreadcrumbItems($maxDepth, $stopAtPageType, $showHidden);
        $pages->remove($this->owner);

        $template = SSViewer::create('Plastyk\Dashboard\Includes\BreadcrumbsTemplate');
        return $template->process($this->owner->customise(new ArrayData([
            'Pages' => $pages,
            'Delimiter' => $delimiter,
        ])));
    }
}
