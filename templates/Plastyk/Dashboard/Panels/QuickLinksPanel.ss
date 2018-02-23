<div class="dashboard-panel quick-links-panel">

	<% if $CanViewPages %>
	<a href="admin/pages/">
		<span class="dashboard-icon fa fa-sitemap" aria-hidden="true"></span>
		<% _t('CMSPagesController.MENUTITLE','Pages') %>
	</a>
	<% end_if %>

	<% if $CanViewUsers %>
	<a href="admin/security/">
		<span class="dashboard-icon fa fa-users" aria-hidden="true"></span>
		<% _t('SecurityAdmin.MENUTITLE','Security') %>
	</a>
	<% end_if %>

	<% if $CanViewSettings %>
	<a href="admin/settings/">
		<span class="dashboard-icon fa fa-cogs" aria-hidden="true"></span>
		<% _t('CMSSettingsController.MENUTITLE','Settings') %>
	</a>
	<% end_if %>

</div>
