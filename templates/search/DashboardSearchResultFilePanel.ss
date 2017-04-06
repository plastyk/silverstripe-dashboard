<% if $Results %>
<div class="dashboard-panel dashboard-search" data-panel-class="$PanelClassName">
	<h3><a href="admin/assets/">Files</a></h3>
	<table class="table">
		<thead>
			<tr>
				<th>Title</th>
			</tr>
		</thead>
		<tbody>
			<% loop $Results %>
			<tr>
				<td class="link">
					<a href="admin/assets/EditForm/field/File/item/{$ID}/edit">
						$Title
						<div class="note">$Filename</div>
					</a>
				</td>
			</tr>
			<% end_loop %>
		</tbody>
	</table>
	<% include DashboardSearchPagination Results=$Results %>
</div>
<% end_if %>
