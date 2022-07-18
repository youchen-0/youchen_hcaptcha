<?php
if (!defined('SYSTEM_ROOT')) {
	die('Insufficient Permissions');
}
if (ROLE !== 'admin') {
	msg('权限不足!');
	die;
}
switch ($_GET['action']) {
	case 'ok': //成功回显
		echo '<div class="alert alert-success">设置保存成功</div>';
		break;
	case 'test': //测试验证码
		$response = get_hcaptcha(option::get('youchen_hcaptcha_secretkey'), $_POST['h-captcha-response'], $_SERVER["REMOTE_ADDR"]);
		if ($response->success) {
			echo '<div class="alert alert-success">验证码测试成功!</div>';
		} else {
			switch ($response->errorcodes) {
				case '{[0] => "missing-input-secret"}':
				case '{[0] => "invalid-input-secret"}':
					echo '<div class="alert alert-danger">验证码配置错误!</div>';
					break;
				case '{[0] => "timeout-or-duplicate"}':
					echo '<div class="alert alert-danger">验证码已超时!请重新验证</div>';
					break;
				default:
					echo '<div class="alert alert-danger">验证码验证失败!请重新验证</div>';
			}
		}
		break;
	case 'save': //保存设置
		option::set('youchen_hcaptcha_register', intval($_POST['youchen_hcaptcha_register']));
		option::set('youchen_hcaptcha_login', intval($_POST['youchen_hcaptcha_login']));
		option::set('youchen_hcaptcha_sitekey', $_POST['youchen_hcaptcha_sitekey']);
		option::set('youchen_hcaptcha_secretkey', $_POST['youchen_hcaptcha_secretkey']);
		option::set('youchen_hcaptcha_theme', $_POST['youchen_hcaptcha_theme']);
		ReDirect(SYSTEM_URL . 'index.php?mod=admin:setplug&plug=youchen_hcaptcha&action=ok');
		die;
	default:
		break;
}


?>
<h3>hcaptcha验证码设置</h3>
<form action="index.php?mod=admin:setplug&plug=youchen_hcaptcha&action=save" method="post">
	<div class="table-responsive">
		<input type="number" name="cron_asyn" value="0" hidden="" />
		<table class="table table-hover">
			<thead>
				<tr>
					<th style="width:40%">参数</th>
					<th>值</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><b>注册验证码</b></td>
					<td><input type="checkbox" name="youchen_hcaptcha_register" id="youchen_hcaptcha_register" value="1" <?php echo option::get('youchen_hcaptcha_register') ? 'checked' : ''; ?> /> 在注册界面显示验证码</td>
				</tr>
				<tr>
					<td><b>登录验证码</b></td>
					<td><input type="checkbox" name="youchen_hcaptcha_login" id="youchen_hcaptcha_login" value="1" <?php echo option::get('youchen_hcaptcha_login') ? 'checked' : ''; ?> /> 在登录界面显示验证码</td>
				</tr>

				<tr>
					<td><b>Site Key</b></td>
					<td><input type="text" name="youchen_hcaptcha_sitekey" id="youchen_hcaptcha_sitekey" value="<?php echo option::get('youchen_hcaptcha_sitekey') ?>" class="form-control" /></td>
				</tr>
				<tr>
					<td><b>Serect Key</b></td>
					<td><input type="text" name="youchen_hcaptcha_secretkey" id="youchen_hcaptcha_secretkey" value="<?php echo option::get('youchen_hcaptcha_secretkey') ?>" class="form-control" /></td>
				</tr>
				<tr>
					<td><b>验证码样式</b></td>
					<td>
						<div class="input-group">
							<select name="youchen_hcaptcha_theme" class="form-control">
								<option value="light" <?php echo option::get('youchen_hcaptcha_theme') == 'light' ? 'selected' : ''; ?>>浅色</option>
								<option value="dark" <?php echo option::get('youchen_hcaptcha_theme') == 'dark' ? 'selected' : ''; ?>>深色</option>
							</select>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br />
	<input type="submit" class="btn btn-primary" value="提交更改" />&nbsp;&nbsp;&nbsp;
</form>

<br /><br /><br />
<div class="well">
	<b>测试验证码：</b><br />
	<form action="index.php?mod=admin:setplug&plug=youchen_hcaptcha&action=test" method="post">
		<?php youchen_hcaptcha_show(); ?>
		<button type="submit" class="btn btn-primary">提交</button>
	</form>
</div>