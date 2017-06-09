<% if $Results %>
<div class="dashboard-panel dashboard-search" data-panel-class="$PanelClassName">
	<h3><a href="admin/security/"><% _t('Member.PLURALNAME', 'Members') %></a></h3>
	<table class="table">
		<thead>
			<tr>
				<th><% _t('SearchPanel.NAME', 'Name') %></th>
			</tr>
		</thead>
		<tbody>
			<% loop $Results %>
			<tr>
				<td class="link">
					<a href="$SearchResultCMSLink">
						$Title
						<div class="note">$Email</div>
					</a>
				</td>
			</tr>
			<% end_loop %>
		</tbody>
	</table>
	<% include DashboardSearchPagination Results=$Results %>
</div>
<% end_if %>
