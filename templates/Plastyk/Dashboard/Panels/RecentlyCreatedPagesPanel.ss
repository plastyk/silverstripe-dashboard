<div class="dashboard-panel">
	<h3><a href="admin/pages/"><% _t('RecentlyCreatedPagesPanel.PANELTITLE', 'Recently created pages') %></a></h3>
	<% if $Results %>
	<table class="table">
		<thead>
			<tr>
				<th><% _t('RecentlyCreatedPagesPanel.TITLE', 'Title') %></th>
				<th><% _t('RecentlyCreatedPagesPanel.CREATED', 'Created') %></th>
			</tr>
		</thead>
		<tbody>
			<% loop $Results %>
			<tr>
				<td class="link">
					<a href="$CMSEditLink">
						$Title
						<div class="note">$Breadcrumbs(4, true)</div>
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
	<p><% _t('RecentlyCreatedPagesPanel.NORECENTLYCREATEDPAGES', 'No pages created in the last six months.') %></p>
	<% end_if %>
</div>
