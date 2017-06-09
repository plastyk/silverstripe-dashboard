<div class="dashboard-panel more-information-panel">
	<p>
		<span class="fa fa-lightbulb-o" aria-hidden="true"></span>
		<% _t('MoreInformationPanel.CUSTOMPANELSAVAILABLE', 'Custom dashboard panels are available.') %>
		<% _t('MoreInformationPanel.CONTACT', 'Contact') %>
		<% if $DashboardContactEmail %><a href="mailto:{$DashboardContactEmail}"><% end_if %>
		<% if $DashboardContactName %>$DashboardContactName<% else %><% _t('MoreInformationPanel.YOURWEBDEVELOPER', 'your web developer') %><% end_if %>
		<% if $DashboardContactEmail %></a><% end_if %>
		<% _t('MoreInformationPanel.IFYOUWOULDLIKETODISCUSS', 'if you would like to discuss.') %>
	</p>
</div>
