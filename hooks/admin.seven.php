<?php if (!defined('CC_DS')) die('Access Denied');

require_once CC_ROOT_DIR . '/modules/plugins/seven/seven.class.php';

$class = new seven;

$GLOBALS['smarty']->assign('CUSTOMER', $class->getCustomer((int)$_GET['customer_id']));
$GLOBALS['smarty']->assign('SEVEN', $class->getModuleConfig());

$page_content = $class->display();
