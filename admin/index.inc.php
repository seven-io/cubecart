<?php if (!defined('CC_DS')) die('Access Denied');

if (isset($_POST['module']))
    $_POST['module'] = array_merge($GLOBALS['config']->get('seven'), $_POST['module']);

$module = new Module(__FILE__, $_GET['module'], 'admin/index.tpl', true);

$page_content = $module->display();
