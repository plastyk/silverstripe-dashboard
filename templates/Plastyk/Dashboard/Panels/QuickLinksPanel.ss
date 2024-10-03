<div class="dashboard-panel quick-links-panel">
	<% loop $QuickLinks %>
	<a href="{$Url}">
		<span class="dashboard-icon fa {$Icon}" aria-hidden="true"></span>
		{$Title}
	</a>
	<% end_loop %>
</div>