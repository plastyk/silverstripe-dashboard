
$showPanel(UpdatePanel)

$showPanel(SearchPanel)

<h1>$SiteConfig.Title</h1>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			$showPanel(QuickLinksPanel)
		</div>
	</div>

	<% if $canViewPanel(RecentlyEditedPagesPanel) || $canViewPanel(RecentlyCreatedPagesPanel) || $canViewPanel(UsefulLinksPanel) %>
	<div class="row">
		<div class="col-4">
			$showPanel(RecentlyEditedPagesPanel)
		</div>
		<div class="col-4">
			$showPanel(RecentlyCreatedPagesPanel)
		</div>
		<div class="col-4">
			$showPanel(UsefulLinksPanel)
		</div>
	</div>
	<% end_if %>

	<div class="row">
		<div class="col-12">
			$showPanel(MoreInformationPanel)
		</div>
	</div>
</div>
