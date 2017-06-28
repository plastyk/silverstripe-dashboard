(function($) {
	$.entwine('ss', function($) {

		$('.cms-content.DashboardAdmin .dashboard-panel a').entwine({
			onclick: function(e) {
				var isExternal = $.path.isExternal(this.attr('href'));
				if (e.which > 1 || isExternal || this.attr('target') == "_blank" || !this.attr('href')) {
					return;
				}

				e.preventDefault();

				var url = this.attr('href');

				if (!isExternal) {
					url = $('base').attr('href') + url;
				}

				if (!$('.cms-container').loadPanel(url)) {
					if (url.indexOf('admin/pages/') !== -1) {
						$('.cms-menu-list li#Menu-CMSPagesController').select();
					}
					return false;
				}

				item.select();
			}
		});
	});
})(jQuery);
