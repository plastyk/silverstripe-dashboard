<div class="container-fluid">

    <div class="dashboard-search">
    	$DashboardSearchForm
    </div>

<% if $SearchValue %>
	<div class="row">
		<div class="col-12">
            <h2><%t SearchPanel.SEARCHRESULTSFOR 'Search Results for' %> <em>'$SearchValue'</em></h2>

            <% if $SearchMessage %>
            <p class="note">$SearchMessage</p>
            <% end_if %>
        </div>
    </div>

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
    			<p><%t SearchPanel.NORESULTS 'Sorry, no results found.' %></p>
    		</div>
    	</div>
    	<% end_if %>
    </div>
<% end_if %>
</div>
