<div class="dashboard-panel update-panel closed">
	<div class="panel-head">
		<span class="fa fa-exclamation-triangle" aria-hidden="true"></span>
		<strong>New SilverStripe CMS updates are available.</strong>
		<% if $CurrentSilverStripeVersion %>
		Your version is {$CurrentSilverStripeVersion}.
		<% end_if %>
		<% if $LatestSilverStripeVersion %>
		The latest version is {$LatestSilverStripeVersion}.
		<% end_if %>
		<a class="read-more-link">Read more</a>
		<span class="fa fa-angle-right" aria-hidden="true"></span>
	</div>

	<div class="panel-body">
		<% if $UpdateVersionLevel == 'major' %>
		<p>The available update is a major version update. A major version update consists of:</p>
		<ul class="list">
			<li>Security patches</li>
			<li>Bug fixes</li>
			<li>New features and enhancements</li>
			<li>Improved admin section</li>
			<li>Site performance improvements</li>
		</ul>
		<% else_if $UpdateVersionLevel == 'minor' %>
		<p>The available update is a minor version update. A minor version update consists of:</p>
		<ul class="list">
			<li>Security patches</li>
			<li>Bug fixes</li>
			<li>New features and enhancements</li>
			<li>Site performance improvements</li>
		</ul>
		<% else_if $UpdateVersionLevel == 'security' %>
		<p>The available update is a security release. A security release consists of:</p>
		<ul class="list">
			<li>Security patches</li>
			<li>Bug fixes</li>
		</ul>
		<% end_if %>

		<p>
			If you would like to update to the latest version please contact
			<% if $DashboardContactEmail %><a href="mailto:{$DashboardContactEmail}"><% end_if %><% if $DashboardContactName %>$DashboardContactName<% else %>your web developer<% end_if %><% if $DashboardContactEmail %></a><% end_if %>.
		</p>
	</div>
</div>
