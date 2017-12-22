---
layout: default
---

# Changing the main dashboard template

We can customise the dashboard to display anything that we like.

In this example we will change the main dashboard template to display only a few panels.

## Customising the main dashboard template

Say we would like to remove some of the default panels, change the order of panels and change some panel widths.

We create a custom `DashboardPanels.ss` template to modify the dashboard. Copy the original `DashboardPanels.ss` to `dashboard-custom/templates/DashboardPanels.ss` and edit the template as desired:

```html
$showPanel(UpdatePanel)

$showPanel(SearchPanel)

<h1>$SiteConfig.Title</h1>

<div class="container-fluid">
	<% if $canViewPanel(RecentlyEditedPagesPanel) || $canViewPanel(RecentlyCreatedPagesPanel) %>
	<div class="row">
		<div class="col-4">
			$showPanel(RecentlyCreatedPagesPanel)
		</div>
		<div class="col-8">
			$showPanel(RecentlyEditedPagesPanel)
		</div>
	</div>
	<% end_if %>
</div>
```

We can modify this template to add new panels, remove existing panels, change the order of panels and change the widths of the panels displayed.
