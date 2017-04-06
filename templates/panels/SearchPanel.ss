<div class="dashboard-search">
	$DashboardSearchForm
</div>

<% if $SearchValue %>
<h2>Search Results for <em>'$SearchValue'</em></h2>

<% if $SearchMessage %>
<p class="note">$SearchMessage</p>
<% end_if %>

<div class="dashboard-row">
	<% if $SearchResults %>
		<% loop $SearchResults %>
		<% if $Results %>
			$Results
		<% end_if %>
		<% end_loop %>
	<% else %>
	<div class="dashboard-panel">
		<p>Sorry, no results found.</p>
	</div>
	<% end_if %>
</div>
<% end_if %>
