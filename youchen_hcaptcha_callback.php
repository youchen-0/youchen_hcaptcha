<?php
if (!defined('SYSTEM_ROOT')) {
	die('Insufficient Permissions');
}

function callback_init()
{
	// 插件配置
	option::add('youchen_hcaptcha_register', 1);
	option::add('youchen_hcaptcha_login', 1);
	option::add('youchen_hcaptcha_theme', 'light');
	option::add('youchen_hcaptcha_sitekey', '');
	option::add('youchen_hcaptcha_secretkey', '');
}

function callback_remove()
{
	// 禁用插件时移除配置文件
	option::del('youchen_hcaptcha_register');
	option::del('youchen_hcaptcha_login');
	option::del('youchen_hcaptcha_theme');
	option::del('youchen_hcaptcha_site_key');
	option::del('youchen_hcaptcha_secret_key');
}
