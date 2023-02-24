<?php if (!defined('CC_DS')) die('Access Denied');

require_once CC_ROOT_DIR . '/modules/plugins/seven/seven.class.php';

(new seven)->renderCustomerListScript();
