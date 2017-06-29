
$showPanel(UpdatePanel)

$showPanel(SearchPanel)

<h1>$SiteConfig.Title</h1>

$showPanel(QuickLinksPanel)

<% if $canViewPanel(RecentlyEditedPagesPanel) || $canViewPanel(RecentlyCreatedPagesPanel) || $canViewPanel(UsefulLinksPanel) %>
<div class="dashboard-row">
	$showPanel(RecentlyEditedPagesPanel)

	$showPanel(RecentlyCreatedPagesPanel)

	$showPanel(UsefulLinksPanel)
</div>
<% end_if %>

$showPanel(MoreInformationPanel)
