(function ($) {
	$.entwine('ss', function ($) {

		$('.cms-content.DashboardAdmin .dashboard-search-form').entwine({
			onsubmit: function () {
				// Remove empty elements and make the URL prettier
				var url;
				var searchInput;

				url = this.attr('action');

				searchInput = this.find('input[name="Search"]');

				if (searchInput.val().length) {
					url = $.path.addSearchParams(url, {Search: searchInput.val()});
				}

				var container = this.closest('.cms-container');
				container.loadPanel(url);

				return false;
			}
		});

		$('.cms-content.DashboardAdmin .dashboard-search.dashboard-panel[data-panel-class] .dashboard-pagination a').entwine({
			onclick: function (e) {
				e.preventDefault();

				var panelClass = $(this).parents('.dashboard-panel[data-panel-class]').first().data('panel-class');
				var panelClassWithSlashes = panelClass.replace(/-/g, '\\');
				var url = $(this).attr('href');
				if (url.indexOf('&panel-class=' + panelClassWithSlashes) === -1) {
					url = url + '&panel-class=' + panelClassWithSlashes;
				}

				$.ajax(url)
					.done(function (response) {
						if (response) {
							var panelSelector = '.dashboard-search.dashboard-panel[data-panel-class="' + panelClass + '"]';
							$('.cms-content.DashboardAdmin ' + panelSelector).html($(response).html());
							$('html, body').animate({
								scrollTop: $('.cms-content.DashboardAdmin ' + panelSelector).offset().top
							});
						}
						$('.cms-content').removeClass('loading');
					})
					.fail(function (xhr) {
						alert('Error: ' + xhr.responseText);
						$('.cms-content').removeClass('loading');
					});
				$('.cms-content').addClass('loading');
			}
		});
	});
})(jQuery);
