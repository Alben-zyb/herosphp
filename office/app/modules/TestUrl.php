<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-10 上午11:35
 * @function
 */

$SKIP_URLS=[
    '/**',
    '/**/**/get**',
    '/**/user/**',
    '/**/**/add',
    '/admin/**',
    '/admin/**/add',
    '/common/**/add',
    '/**/**/add',
    '/**/user/add',
    '/admin/user/**',
    '/common/user/add',
    '/**/admin/**/**/add',
];

$url='/common/public/getData';


foreach ($SKIP_URLS as $index=>$SKIP_URL) {
    $match='/^'; //正则模式匹配表达式
    $skip_url_arr=explode('/',$SKIP_URL);//将跳过skip_url拆分成模块(支持多级模块)，控制器，方法

    //循环添加匹配
    for ($i=0;$i<sizeof($skip_url_arr);$i++){
        if($skip_url_arr[$i]=='**'){
            //若是通配符，可匹配大小写数字组成的任意的/模块/控制器/方法
            $match.='[a-zA-Z1-9]+\/';
        }elseif (preg_match('/^[a-zA-Z1-9]+[\*]{2}$/', $skip_url_arr[$i])) {
            $match .= substr($skip_url_arr[$i],0,-2).'[a-zA-Z1-9]+\/';
        } else{
            //若不是通配符，只能匹配指定的/模块/控制器/方法
            $match.=$skip_url_arr[$i].'\/';
        }

    }
    $match=substr($match,0,-2); //去除末尾多余的转义/:'\/'
    $match.='/'; //添加模式匹配表达式结束符
    echo ($index+1).': '.$match."\n";
    if(preg_match($match,$url)){
        echo 'false'."\n";
    }else{
        echo 'true'."\n";
    }

    //var_dump($skip_url_arr);

}








/*
$urlRrr=explode('/',$url);//将url拆分成模块，控制器，方法

var_dump($urlRrr);

//通配符存在以下三种情况

$all='/**'; //全部通配
$module='/'.$urlRrr[1].'/**'; //模块通配
$controller='/'.$urlRrr[1].'/'.$urlRrr[2].'/**'; //控制器通配

if(in_array($all, $SKIP_URLS)){
    echo 'false '.$all."\n";
    die();
}

if(in_array($module, $SKIP_URLS)){
    echo 'false '.$module."\n";
    die();
}
if(in_array($controller, $SKIP_URLS)){
    echo 'false '.$controller."\n";
    die();
}

if(in_array($url, $SKIP_URLS)){
    echo "false".$url."\n";
    die();
}*/