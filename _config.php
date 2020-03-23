<?php

define('DASHBOARD_ADMIN_DIR', basename(dirname(__FILE__)));

DashboardAdmin::config()->menu_icon = DASHBOARD_ADMIN_DIR . '/images/treeicons/dashboard.png';

if (!class_exists('SS_Object')) {
    class_alias('Object', 'SS_Object');
}
