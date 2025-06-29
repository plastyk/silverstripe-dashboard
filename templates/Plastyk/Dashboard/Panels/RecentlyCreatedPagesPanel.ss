<div class="dashboard-panel">
	<h3><a href="{$AdminURL}/pages/"><%t RecentlyCreatedPagesPanel.PANELTITLE 'Recently created pages' %></a></h3>
	<% if $Results %>
	<table class="table">
		<thead>
			<tr>
				<th><%t RecentlyCreatedPagesPanel.TITLE 'Title' %></th>
				<th><%t RecentlyCreatedPagesPanel.CREATED 'Created' %></th>
			</tr>
		</thead>
		<tbody>
			<% loop $Results %>
			<tr>
				<td class="link">
					<a href="$CMSEditLink">
						$Title
						<div class="note">$DashboardBreadcrumbs(4)</div>
					</a>
				</td>
				<td class="link date">
					<a href="$CMSEditLink" title="$Created.Nice">$Created.Ago</a>
				</td>
			</tr>
			<% end_loop %>
		</tbody>
	</table>
	<% else %>
	<p><%t RecentlyCreatedPagesPanel.NORECENTLYCREATEDPAGES 'No pages created in the last six months.' %></p>
	<% end_if %>
</div>
