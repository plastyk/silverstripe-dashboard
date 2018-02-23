<form $FormAttributes>
	<% if $Message %>
	<p id="{$FormName}_error" class="message $MessageType">$Message</p>
	<% else %>
	<p id="{$FormName}_error" class="message $MessageType" style="display: none"></p>
	<% end_if %>

	<% loop $Fields %>
	$Field
	<% end_loop %>

	<button class="submit">
		<span class="dashboard-icon fa fa-search" aria-hidden="true"></span>
	</button>
</form>
