
$showPanel(Plastyk\Dashboard\Panels\UpdatePanel)

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			$showPanel(Plastyk\Dashboard\Panels\SearchPanel)

			<h1>$SiteConfig.Title</h1>
		</div>
	</div>

	<% if $DashboardPanelSections %>
	<% loop $DashboardPanelSections %>
	<div class="row">
		$Me
	</div>
	<% end_loop %>
	<% end_if %>
</div>