(function ($) {
	$.entwine('ss', function ($) {
		$('.cms-content.DashboardAdmin .dashboard-panel a').entwine({
			onclick: function (e) {
				const url = this.attr('href');
				const isExternal = $.path.isExternal(url) && url.indexOf(ss.config.adminUrl) === -1;
				
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
