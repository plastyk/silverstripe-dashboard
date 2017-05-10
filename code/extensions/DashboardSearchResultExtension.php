<?php
class DashboardSearchResultExtension extends DataExtension {

	public function getSearchResultCMSLink() {
		$adminLink = $this->owner->config()->dashboard_admin_link;
		return SSViewer::execute_string($adminLink, $this->owner);
	}
}
