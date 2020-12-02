<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-10 上午10:45
 * @function  验证码类，生成验证码图片
 */

namespace app\admin\utils;


use app\api\service\SMSService;

class Captcha {

    /**
     * 制作图片验证码
     * @param int $width =300,验证码图片默认宽度
     * @param int $height =100,验证码图片默认高度
     * @param int $length =4,验证码图片默认字符数
     * @param string $fonts ='',验证码图片字体，默认为空（内部使用默认字体）
     */
    public static function getCaptcha($width = 300, $height = 100, $length = 4, $fonts = '') {
        //判定字体资源
        if (empty($fonts)) {
            $fonts = 'Hoboken.otf';
        }
        //确定路径
        $fonts = APP_ROOT . 'static/admin/identify/fonts/' . $fonts;

        //制作画布
        $img = imagecreatetruecolor($width, $height);

        //分配背景颜色：随机浅色系
        $bg_color = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
        imagefill($img, 0, 0, $bg_color);


        //获取随机字符
        $captcha = self::getString($length);

        //保存到session
        $_SESSION['captcha'] = strtolower($captcha); //转换成小写字母,存放cookie

        //将验证码字符写入图片
        for ($i = 0; $i < $length; $i++) {
            //增加颜色
            $c_color = imagecolorallocate($img, mt_rand(0, 140), mt_rand(0, 140), mt_rand(0, 140));

            //写入到图片
            imagettftext($img, mt_rand(60, 70), mt_rand(-45, 45), mt_rand(($width - 20) / ($length) * ($i) + 20, $width / ($length) * ($i) + 20), mt_rand(65, $height - 20), $c_color, $fonts, $captcha[$i]);

        }
        //增加干扰点：*
        for ($i = 0; $i < 50; $i++) {
            //随机颜色
            $dots_color = imagecolorallocate($img, mt_rand(140, 240), mt_rand(140, 240), mt_rand(140, 240));
            //写入内容
            imagestring($img, mt_rand(1, 5), mt_rand(0, $width), mt_rand(0, $height), '*', $dots_color);
        }

        //增加干扰线
        for ($i = 0; $i < 20; $i++) {
            //线段颜色
            $line_color = imagecolorallocate($img, mt_rand(80, 240), mt_rand(80, 240), mt_rand(80, 240));
            //制作线段
            imageline($img, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $line_color);
        }
        //输出资源
        ob_clean();//不加不能显示，不知什么原因(原因：由于编码的问题，不同编译器在处理HTML文件时所涉及的BOM标签问题，需要ob_clean()清除输出缓冲区以保证图片能正常显示)
        header("content-type:image/png"); // 设置创建图像的格式
        imagepng($img);

        //销毁资源
        imagedestroy($img);

    }

    /**
     * 随机生成图片验证码字符串
     * @param int $length
     * @param bool $onlyNum
     * @return string
     */
    private static function getString($length = 4, $onlyNum = false) {
        //定义变量保存数据
        $captcha = '';

        //循环获取随机数据
        for ($i = 0; $i < $length; $i++) {
            //确定随机数字、大写字母、小写字母
            $case = mt_rand(1, 3);
            if ($onlyNum) {
                $case = 1;
            }
            switch ($case) {
                case 1:                //数字：48-57分别代表：0-9
                    $captcha .= chr(mt_rand(48, 57));
                    break;
                case 2:                //数字：65-90分别代表：小写字母
                    $captcha .= chr(mt_rand(65, 90));
                    break;
                case 3:                //数字：97-122分别代表：大写字母
                    $captcha .= chr(mt_rand(97, 122));
                    break;
            }
        }
        //返回给调用处
        return $captcha;
    }

    /**
     * 验证图片验证码的合法性
     * @param $captcha
     * @return bool
     */
    public static function checkCaptcha($captcha) {
        //与session中存的进行对比
        return (strtolower($captcha) === $_SESSION['captcha']);
    }

    /**
     * 生成手机验证码
     * @param int $length
     * @return string
     */
    public static function getPhoneCode($length = 6) {
        $phoneCode=self::getString($length, true); //生成手机验证码数字串

        //保存到session
        $_SESSION['phoneCode'] = $phoneCode; //存放cookie
        return $phoneCode;

    }

    /**
     * 验证手机验证码的合法性
     * @param $phoneCode
     * @return bool
     */
    public static function checkPhoneCode($phoneCode) {
        //与session中存的进行对比
        return (trim($phoneCode) === $_SESSION['phoneCode']);
    }

}