
<% if $canViewPanel(UpdatePanel) %>
$showPanel(UpdatePanel)
<% end_if %>

$showPanel(SearchPanel)

<h1>$SiteConfig.Title</h1>

$showPanel(QuickLinksPanel)

<div class="dashboard-row">
	<% if $canViewPanel(RecentlyEditedPagesPanel) %>
		$showPanel(RecentlyEditedPagesPanel)
	<% end_if %>

	<% if $canViewPanel(RecentlyCreatedPagesPanel) %>
		$showPanel(RecentlyCreatedPagesPanel)
	<% end_if %>


	$showPanel(UsefulLinksPanel)
</div>

<% if $canViewPanel(MoreInformationPanel) %>
$showPanel(MoreInformationPanel)
<% end_if %>
