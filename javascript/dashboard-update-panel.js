(function ($) {
	$.entwine('ss', function ($) {

		$('.cms-content.DashboardAdmin .dashboard-panel.update-panel .panel-head').entwine({
			onclick: function () {
				$('.cms-content.DashboardAdmin .dashboard-panel.update-panel').toggleClass('open');
				$('.cms-content.DashboardAdmin .dashboard-panel.update-panel .panel-body').slideToggle();
			}
		});
	});
})(jQuery);
