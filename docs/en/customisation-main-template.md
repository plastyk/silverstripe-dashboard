---
layout: default
---

# Changing the main dashboard template

We can customise the dashboard to display anything that we like.

In this example we will change the main dashboard template to display only a few panels.

## Customising the main dashboard template

Say we would like to remove some of the default panels, change the order of panels and change some panel widths.

First we create a `dashboard-custom` folder in our root directory to house our custom dashboard code. To enable the `dashboard-custom` directory to be picked up by SilverStripe we must create a `_config` directory inside `dashboard-custom`.

We create a custom `DashboardPanels.ss` template to modify the dashboard. Copy the original `DashboardPanels.ss` to `dashboard-custom/templates/DashboardPanels.ss` and edit the template as desired:

```html
$showPanel(Plastyk\Dashboard\Panels\UpdatePanel)


<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			$showPanel(Plastyk\Dashboard\Panels\SearchPanel)

			<h1>$SiteConfig.Title</h1>
		</div>
	</div>

	<% if $canViewPanel(Plastyk\Dashboard\Panels\RecentlyEditedPagesPanel) || $canViewPanel(Plastyk\Dashboard\Panels\RecentlyCreatedPagesPanel) %>
	<div class="row">
		<div class="col-6">
			$showPanel(Plastyk\Dashboard\Panels\RecentlyCreatedPagesPanel)
		</div>
		<div class="col-6">
			$showPanel(Plastyk\Dashboard\Panels\RecentlyEditedPagesPanel)
		</div>
	</div>
	<% end_if %>
</div>
```

We can modify this template to add new panels, remove existing panels, change the order of panels and change the widths of the panels displayed.
