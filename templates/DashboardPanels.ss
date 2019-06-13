
$showPanel(Plastyk\Dashboard\Panels\UpdatePanel)


<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			$showPanel(Plastyk\Dashboard\Panels\SearchPanel)

			<h1>$SiteConfig.Title</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			$showPanel(Plastyk\Dashboard\Panels\QuickLinksPanel)
		</div>
	</div>

	<% if $canViewPanel(Plastyk\Dashboard\Panels\RecentlyEditedPagesPanel) || $canViewPanel(Plastyk\Dashboard\Panels\RecentlyCreatedPagesPanel) || $canViewPanel(Plastyk\Dashboard\Panels\UsefulLinksPanel) %>
	<div class="row">
		<div class="col-xl-4">
			$showPanel(Plastyk\Dashboard\Panels\RecentlyEditedPagesPanel)
		</div>
		<div class="col-xl-4">
			$showPanel(Plastyk\Dashboard\Panels\RecentlyCreatedPagesPanel)
		</div>
		<div class="col-xl-4">
			$showPanel(Plastyk\Dashboard\Panels\UsefulLinksPanel)
		</div>
	</div>
	<% end_if %>

	<div class="row">
		<div class="col-12">
			$showPanel(Plastyk\Dashboard\Panels\MoreInformationPanel)
		</div>
	</div>
</div>
