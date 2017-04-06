<?php

class UpdatePanel extends DashboardPanel {

	public function canView($member = null) {
		if (Permission::check('CMS_ACCESS_ADMIN')) {
			return !$this->isCurrentSilverStripeVersion();
		}
		return false;
	}

	public function getData() {
		$data = parent::getData();

		$data['CurrentSilverStripeVersion'] = $this->getCurrentSilverStripeVersion();
		$data['LatestSilverStripeVersion'] = $this->getLatestSilverStripeVersion();
		$data['UpdateVersionLevel'] = $this->getUpdateVersionLevel();
		$data['DashboardContactEmail'] = DashboardAdmin::config()->contact_email ? : false;
		$data['DashboardContactName'] = DashboardAdmin::config()->contact_name ? : false;

		return $data;
	}

	public function init() {
		parent::init();
		Requirements::css(DASHBOARD_ADMIN_DIR . '/css/dashboard-update-panel.css');
		Requirements::javascript(DASHBOARD_ADMIN_DIR . '/javascript/dashboard-update-panel.js');
	}

	public function getCurrentSilverStripeVersion() {
		$currentVersion = false;
		if (!Session::get('silverstripe_current_version')) {
			$versions = explode(', ', Injector::inst()->get('LeftAndMain')->CMSVersion());
			if ($versions) {
				foreach ($versions as $version) {
					if (strpos($version, 'Framework: ') !== false) {
						$currentVersion =  substr($version, 11);
						Session::set('silverstripe_current_version', $currentVersion);
					}
				}
			}
		} else {
			$currentVersion = Session::get('silverstripe_current_version');
		}
		return $currentVersion;
	}

	public function getLatestSilverStripeVersion() {
		$latestVersion = false;
		if (!Session::get('silverstripe_latest_version')) {
			if (!$latestVersion) {
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, 'https://packagist.org/feeds/package.silverstripe/framework.rss');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$package_feed = curl_exec($curl);
				curl_close($curl);
				if ($package_feed) {
					$xml = simplexml_load_string($package_feed);
					$json = json_encode($xml);
					$package_array = json_decode($json, true);
					if ($package_array && isset($package_array['channel']['item'])) {
						for ($i = 0; $i < count($package_array['channel']['item']); $i++) {
							$title = $package_array['channel']['item'][$i]['title'];
							if (isset($title) && strpos($title, 'alpha') === false && strpos($title, 'beta') === false && strpos($title, 'rc') === false) {
								$latestVersion = preg_replace('@[^0-9\.]+@i', '', $title);
								Session::set('silverstripe_latest_version', $latestVersion);
								break 1;
							}
						}
					}
				}
			}
		} else {
			$latestVersion = Session::get('silverstripe_latest_version');
		}
		return $latestVersion;
	}

	public function isCurrentSilverStripeVersion() {
		if ($this->getCurrentSilverStripeVersion() && $this->getLatestSilverStripeVersion()) {
			return $this->getCurrentSilverStripeVersion() >= $this->getLatestSilverStripeVersion();
		}
		return false;
	}

	public function getUpdateVersionLevel() {
		if ($this->isCurrentSilverStripeVersion()) {
			return false;
		}
		$currentVersion = explode('.', $this->getCurrentSilverStripeVersion());
		$latestVersion = explode('.', $this->getLatestSilverStripeVersion());

		if (count($currentVersion) === 3 && count($latestVersion) === 3) {
			if ($latestVersion[0] > $currentVersion[0]) {
				return 'major';
			}
			if ($latestVersion[1] > $currentVersion[1]) {
				return 'minor';
			}
			if ($latestVersion[2] > $currentVersion[2]) {
				return 'security';
			}
		}
		return false;
	}

}
