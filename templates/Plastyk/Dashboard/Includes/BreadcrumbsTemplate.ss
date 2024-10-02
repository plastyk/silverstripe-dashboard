<% if $Pages %>
	<% loop $Pages %>$MenuTitle.XML <% if not $IsLast %>$Up.Delimiter.RAW <% end_if %><% end_loop %>
<% end_if %>