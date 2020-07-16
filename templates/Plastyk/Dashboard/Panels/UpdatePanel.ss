<div class="dashboard-panel update-panel closed">
	<div class="panel-head col-12">
		<span class="dashboard-icon fa fa-exclamation-triangle" aria-hidden="true"></span>
		<strong><% _t('UpdatePanel.NEWSILVERSTRIPEUPDATESAVAILABLE', 'New Silverstripe CMS updates are available.') %></strong>
		<% if $CurrentSilverstripeVersion %>
		<% _t('UpdatePanel.YOURVERSIONIS', 'Your version is') %> {$CurrentSilverstripeVersion}.
		<% end_if %>
		<% if $LatestSilverstripeVersion %>
		<% _t('UpdatePanel.LATESTVERSIONIS', 'The latest version is') %> {$LatestSilverstripeVersion}.
		<% end_if %>
		<a class="read-more-link" tabindex=""><% _t('UpdatePanel.READMORE', 'Read more') %></a>
		<span class="dashboard-icon fa fa-chevron-right" aria-hidden="true"></span>
	</div>

	<div class="panel-body col-12">
		<% if $UpdateVersionLevel == 'major' %>
		<p><% _t('UpdatePanel.MAJORVERSIONMESSAGE', 'The available update is a major version update. A major version update consists of:') %></p>
		<ul class="list">
			<li><% _t('UpdatePanel.SECURITYPATCHES', 'Security patches') %></li>
			<li><% _t('UpdatePanel.BUGFIXES', 'Bug fixes') %></li>
			<li><% _t('UpdatePanel.NEWFEATURES', 'New features and enhancements') %></li>
			<li><% _t('UpdatePanel.IMPROVEDADMIN', 'Improved admin section') %></li>
			<li><% _t('UpdatePanel.SITEPERFORMANCE', 'Site performance improvements') %></li>
		</ul>
		<% else_if $UpdateVersionLevel == 'minor' %>
		<p><% _t('UpdatePanel.MINORVERSIONMESSAGE', 'The available update is a minor version update. A minor version update consists of:') %></p>
		<ul class="list">
			<li><% _t('UpdatePanel.SECURITYPATCHES', 'Security patches') %></li>
			<li><% _t('UpdatePanel.BUGFIXES', 'Bug fixes') %></li>
			<li><% _t('UpdatePanel.NEWFEATURES', 'New features and enhancements') %></li>
			<li><% _t('UpdatePanel.SITEPERFORMANCE', 'Site performance improvements') %></li>
		</ul>
		<% else_if $UpdateVersionLevel == 'patch' %>
		<p><% _t('UpdatePanel.SECURITYVERSIONMESSAGE', 'The available update is a security release. A security release consists of:') %></p>
		<ul class="list">
			<li><% _t('UpdatePanel.SECURITYPATCHES', 'Security patches') %></li>
			<li><% _t('UpdatePanel.BUGFIXES', 'Bug fixes') %></li>
		</ul>
		<% end_if %>

		<p>
			$ContactContent
		</p>
	</div>
</div>
