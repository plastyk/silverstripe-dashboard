(function($) {
	$.entwine('ss', function($) {

		$('.cms-content.DashboardAdmin .dashboard-search-form').entwine({
			onsubmit: function(e) {
				// Remove empty elements and make the URL prettier
				var nonEmptyInputs,
					url;

				nonEmptyInputs = this.find(':input:not(:submit)').filter(function() {
					// Use fieldValue() from jQuery.form plugin rather than jQuery.val(),
					// as it handles checkbox values more consistently
					var vals = $.grep($(this).fieldValue(), function(val) { return (val);});
					return (vals.length);
				});

				url = this.attr('action');

				if(nonEmptyInputs.length) {
					url = $.path.addSearchParams(url, nonEmptyInputs.serialize());
				}

				var container = this.closest('.cms-container');
				container.loadPanel(url, "", {}, true);

				return false;
			}
		});

		$('.cms-content.DashboardAdmin .dashboard-search.dashboard-panel[data-panel-class] .pagination a').entwine({
			onclick: function(e) {
				e.preventDefault();

				var panelClass = $(this).parents('.dashboard-panel[data-panel-class]').first().data('panel-class');
				var url = $(this).attr('href');
				if (url.indexOf('&panel-class=' + panelClass) === -1) {
					url = url + '&panel-class=' + panelClass;
				}

				$.ajax(url)
					.done(function (response) {
						var panelSelector = '.dashboard-search.dashboard-panel[data-panel-class="' + panelClass + '"]';
						$('.cms-content.DashboardAdmin ' + panelSelector).html($(response).html());
						$('html, body').animate({
							scrollTop: $('.cms-content.DashboardAdmin ' + panelSelector).offset().top
						});
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
