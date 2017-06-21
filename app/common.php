<?php
use think\Request;

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 向前端返回一个json格式的状态信息
 */
function show($status, $message, $data=array()) 
{
    $result = array(
        'status'  => $status,
        'message' => $message,
        'data'    => $data,
    );
    return json($result);
}

function getMD5password($password)
{
    return MD5(config('MD5_PRE').$password);
}

/**
 * 获取状态值对应的状态
 * @param unknown $status
 * @return string
 */
function getStatus($status)
{
    if($status == 1){
        return "正常";
    } elseif($status == 0){
        return '关闭';
    }
}

/**
 * 获取导航类型
 * @param unknown $type
 * @return string
 */
function getMenuType($type)
{
    if($type == 2){
        return "前端导航";
    } elseif($type == 1){
        return "后台菜单";
    }
}

/**
 * 更改当前访问菜单栏样式
 * @param unknown $navc
 * @return string
 */
function getActive($navc)
{
    $request = Request::instance();
    $controller = strtolower($request->controller());
    if(strtolower($navc) == $controller){
        return 'class="active"';
    } else {
        return '';
    }
}

/**
 * 获取访问路径
 * @param unknown $navm
 * @param unknown $navc
 * @return string
 */
function getadminMenuUrl($navm, $navc)
{
    return "/index.php/".$navm."/".$navc;
}

/**
 *获取新闻来源名称
 */
function getSource($id)
{
    $source = config('SOURCE');
    return $source[$id];
}

/**
 * 获取栏目名称
 */
function getCatName($menus, $catid)
{
    foreach ($menus as $val){
        $menu[$val['menu_id']] = $val['name'];
    }
    return $menu[$catid];
}

/**
 * 是否有缩略图
 */
function isThumb($thumb)
{
    if($thumb){
        return '<span style="color:red">有</span>';
    }
    return '无';
}

/**
 * 编辑器图片异步展示
 * @param unknown $status
 * @param unknown $data
 */
function showKind($status, $data)
{
    header('Content-type:application/json;charset=UTF-8');
    if($status==0){
        exit(json_encode(array('error'=>0,'url'=>$data)));
    }
    exit(json_encode(array('error'=>1,'message'=>'上传失败')));
}

function getPositionName($pid, $positions)
{
    foreach ($positions as $position){
        $list[$position['id']] = $position['name'];
    }
    return $list[$pid] ? $list[$pid] : false;
}