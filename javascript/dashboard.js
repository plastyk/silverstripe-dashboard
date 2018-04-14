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

				if (url.indexOf(baseAdminHref) === -1) {
					url = $('base').attr('href') + url;
				}

				if (!$('.cms-container').loadPanel(url)) {
					if (url.indexOf('admin/pages/') !== -1) {
						$('.cms-menu__list li#Menu-SilverStripe-CMS-Controllers-CMSPagesController').select();
					}
					return false;
				}
			}
		});
	});
})(jQuery);
