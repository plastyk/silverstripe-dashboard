<div class="dashboard-search">
	$DashboardSearchForm
</div>

<% if $SearchValue %>
<h2><% _t('SearchPanel.SEARCHRESULTSFOR', 'Search Results for') %> <em>'$SearchValue'</em></h2>

<% if $SearchMessage %>
<p class="note">$SearchMessage</p>
<% end_if %>

<div class="row">
	<% if $SearchResultPanels %>
		<% loop $SearchResultPanels %>
		<% if $Panel %>
			<div class="col-xl-4">
				$Panel
			</div>
		<% end_if %>
		<% end_loop %>
	<% else %>
	<div class="col-xl-4">
		<div class="dashboard-panel">
			<p><% _t('SearchPanel.NORESULTS', 'Sorry, no results found.') %></p>
		</div>
	</div>
	<% end_if %>
</div>
<% end_if %>
