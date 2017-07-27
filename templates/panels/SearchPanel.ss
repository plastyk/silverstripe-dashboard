<div class="dashboard-search">
	$DashboardSearchForm
</div>

<% if $SearchValue %>
<h2><% _t('SearchPanel.SEARCHRESULTSFOR', 'Search Results for') %> <em>'$SearchValue'</em></h2>

<% if $SearchMessage %>
<p class="note">$SearchMessage</p>
<% end_if %>

<div class="dashboard-row">
	<% if $SearchResultPanels %>
		<% loop $SearchResultPanels %>
		<% if $Panel %>
			$Panel
		<% end_if %>
		<% end_loop %>
	<% else %>
	<div class="dashboard-panel">
		<p><% _t('SearchPanel.NORESULTS', 'Sorry, no results found.') %></p>
	</div>
	<% end_if %>
</div>
<% end_if %>
