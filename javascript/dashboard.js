(function ($) {
	$.entwine('ss', function ($) {

		$('.cms-content.DashboardAdmin .dashboard-panel a').entwine({
			onclick: function (e) {
				var baseHref = $('base').attr('href');
				var baseAdminHref = baseHref + 'admin/';
				var url = this.attr('href');
				var isExternal = $.path.isExternal(url) && url.indexOf(baseAdminHref) === -1;
				if (e.which > 1 || isExternal || this.attr('target') === '_blank' || !this.attr('href')) {
					return;
				}

				e.preventDefault();
				$('.cms-content').addClass('loading');
				window.location.href = this.attr('href');
			}
		});
	});
})(jQuery);
