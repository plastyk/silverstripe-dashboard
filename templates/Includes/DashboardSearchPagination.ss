<% if $Results.MoreThanOnePage %>
	<div class="dashboard-pagination">
		<% if $Results.NotFirstPage %>
		<a href="$Results.PrevLink" aria-label="<% _t('SearchPanel.VIEWPREVIOUSPAGE', 'View the previous page') %>">&laquo;</a>
		<% end_if %>
		<% loop $Results.PaginationSummary %>
			<% if $PageNum %>
			<a href="$Link" aria-label="<% _t('SearchPanel.VIEWPAGENUMBER', 'View page number') %> $PageNum" class="go-to-page<% if $CurrentBool %> active<% end_if %>">$PageNum</a>
			<% else %>
			<span>&hellip;</span>
			<% end_if %>
		<% end_loop %>
		<% if $Results.NotLastPage %>
		<a href="$Results.NextLink" aria-label="<% _t('SearchPanel.VIEWNEXTPAGE', 'View the next page') %>">&raquo;</a>
		<% end_if %>
	</div>
<% end_if %>
