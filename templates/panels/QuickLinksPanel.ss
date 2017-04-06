<div class="dashboard-panel quick-links-panel">

	<% if $CanViewPages %>
	<a href="admin/pages/">
		<span class="fa fa-sitemap" aria-hidden="true"></span>
		Pages
	</a>
	<% end_if %>

	<% if $CanViewUsers %>
	<a href="admin/security/">
		<span class="fa fa-users" aria-hidden="true"></span>
		Users
	</a>
	<% end_if %>

	<% if $CanViewRedirects %>
	<a href="admin/misdirection/">
		<span class="fa fa-repeat" aria-hidden="true"></span>
		Redirects
	</a>
	<% end_if %>

	<% if $CanViewSettings %>
	<a href="admin/settings/">
		<span class="fa fa-cogs" aria-hidden="true"></span>
		Settings
	</a>
	<% end_if %>

</div>
