<div class="dashboard-panel">
	<h3><a href="{$AdminURL}/pages/"><%t RecentlyEditedPagesPanel.PANELTITLE 'Recently edited pages' %></a></h3>
	<% if $Results %>
	<table class="table">
		<thead>
			<tr>
				<th><%t RecentlyEditedPagesPanel.TITLE 'Title' %></th>
				<th><%t RecentlyEditedPagesPanel.EDITED 'Edited' %></th>
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
					<a href="$CMSEditLink" title="$LastEdited.Nice">$LastEdited.Ago</a>
				</td>
			</tr>
			<% end_loop %>
		</tbody>
	</table>
	<% else %>
	<p><%t RecentlyEditedPagesPanel.NORECENTLYEDITEDPAGES 'No pages edited in the last six months.' %></p>
	<% end_if %>
</div>