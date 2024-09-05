<div class="col-xl-4">
	<div class="dashboard-panel">
		<h3>$Title</h3>
		<table class="table">
			<tbody>
				<% loop $Links %>
				<tr>
					<td class="link">
						<a href="$Link" target="_blank">
							$Title
							<div class="note">
								$Description
							</div>
						</a>
					</td>
				</tr>
				<% end_loop %>
			</tbody>
		</table>
	</div>
</div>
