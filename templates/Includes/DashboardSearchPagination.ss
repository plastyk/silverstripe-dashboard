<% if $Results.MoreThanOnePage %>
	<ul class="pagination">
		<% if $Results.NotFirstPage %>
		<li><a href="$Results.PrevLink" aria-label="<% _t('SearchPanel.VIEWPREVIOUSPAGE', 'View the previous page') %>">&laquo;</a></li>
		<% end_if %>
		<% loop $Results.PaginationSummary %>
			<% if $PageNum %>
			<li<% if $CurrentBool %> class="active"<% end_if %>><a href="$Link" aria-label="<% _t('SearchPanel.VIEWPAGENUMBER', 'View page number') %> $PageNum" class="go-to-page">$PageNum</a></li>
			<% else %>
			<li><span>&hellip;</span></li>
			<% end_if %>
		<% end_loop %>
		<% if $Results.NotLastPage %>
		<li><a href="$Results.NextLink" aria-label="<% _t('SearchPanel.VIEWNEXTPAGE', 'View the next page') %>">&raquo;</a></li>
		<% end_if %>
	</ul>
<% end_if %>
