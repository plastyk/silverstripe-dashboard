<div class="dashboard-panel">
	<h3><a href="admin/pages/">Recently edited pages</a></h3>
	<% if $Results %>
	<table class="table">
		<thead>
			<tr>
				<th>Title</th>
				<th>Edited</th>
			</tr>
		</thead>
		<tbody>
			<% loop $Results %>
			<tr>
				<td class="link">
					<a href="admin/pages/edit/show/{$ID}">
						$Title
						<div class="note">$Breadcrumbs(4, true)</div>
					</a>
				</td>
				<td class="link">
					<a href="admin/pages/edit/show/{$ID}">$LastEdited.Ago</a>
				</td>
			</tr>
			<% end_loop %>
		</tbody>
	</table>
	<% else %>
	<p>No pages edited in the last six months.</p>
	<% end_if %>
</div>
