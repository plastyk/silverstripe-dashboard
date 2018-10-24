<% if $Results %>
<div class="dashboard-panel dashboard-search" data-panel-class="$PanelClassName">
	<h3><a href="admin/pages/"><% _t('Page.PLURALNAME', 'Pages') %></a></h3>
	<table class="table">
		<thead>
			<tr>
				<th><% _t('SearchPanel.TITLE', 'Title') %></th>
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
			</tr>
			<% end_loop %>
		</tbody>
	</table>
	<% include DashboardSearchPagination Results=$Results %>
</div>
<% end_if %>
