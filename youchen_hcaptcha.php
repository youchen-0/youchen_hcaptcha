<?php
/*
Plugin Name: hCAPTCHA验证码
Version: 1.0
Description: 在注册/注册的时候，要求用户输入 hCAPTCHA 验证码，来起到防止恶意注册和打击密码爆破的作用，基于loyisa的进行recaptcha插件修改
Author: 游辰
Author Email: 2633774175@qq.com
Author URL: https://blog.youchen.cf
For: V3.4+
*/
if (!defined('SYSTEM_ROOT')) {
    die('Insufficient Permissions');
}

//显示验证码
function youchen_hcaptcha_show()
{
    // 检测是否开启注册验证码
    if (SYSTEM_PAGE == 'reg' && option::get('youchen_hcaptcha_register') == 0) {
        return;
    }
    // 检测是否开启登录验证码
    if (SYSTEM_PAGE == 'login' && option::get('youchen_hcaptcha_login') == 0) {
        return;
    }
    show_recaptcha(option::get('youchen_hcaptcha_sitekey'), option::get('youchen_hcaptcha_theme'));
}

// 检查验证码
function youchen_hcaptcha_check()
{
    // 检测是否开启注册验证码
    if (SYSTEM_PAGE == 'admin:reg' && option::get('youchen_hcaptcha_register') == 0) {
        return;
    }
    // 检测是否开启登录验证码
    if (SYSTEM_PAGE == 'admin:login' && option::get('youchen_hcaptcha_login') == 0) {
        return;
    }
    if (!empty($_POST['h-captcha-response'])) {
        // 获取验证码
        $response = get_recaptcha(option::get('youchen_hcaptcha_secretkey'), $_POST['h-captcha-response'], $_SERVER["REMOTE_ADDR"]);
        // 检测验证码 并根据错误代码输出语句
        if (!$response->success) {
            switch ($response->errorcodes) {
                case '{[0] => "missing-input-secret"}':
                case '{[0] => "invalid-input-secret"}':
                    msg('验证码配置错误!');
                    break;
                case '{[0] => "timeout-or-duplicate"}':
                    msg('验证码已超时!请重新验证');
                    break;
                default:
                    msg('验证码验证失败!请重新验证');
            }
        }
    } else {
        msg('验证码验证失败!请重新验证');
    }
}

/**
 * 获取验证码json
 * @param string $secret
 * @param string $response
 * @param string $remoteip
 * @return object ReCaptchaResponse
 */
function get_recaptcha($secret, $response, $remoteip)
{
    // 和hcaptcha服务器二次校验
    $getjsonurl = file_get_contents('https://hcaptcha.com/siteverify?secret=' . $secret . '&response=' . $response . '&remoteip=' . $remoteip);
    // 解析获取到的json
    $response = json_decode($getjsonurl);
    return $response;
}

function show_recaptcha($sitekey, $theme)
{
    echo '<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
  <div class="h-captcha" data-sitekey="' . $sitekey . '" data-theme="' . $theme . '"></div>';
}

// hook登录/注册界面
addAction('reg_page_2', 'youchen_hcaptcha_show');
addAction('login_page_2', 'youchen_hcaptcha_show');
addAction('admin_reg_1', 'youchen_hcaptcha_check');
addAction('admin_login_1', 'youchen_hcaptcha_check');
