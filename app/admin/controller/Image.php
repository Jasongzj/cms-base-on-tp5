<?php
namespace app\admin\controller;
use think\Controller;

class Image extends Common
{
    /**
     * 定义文章上传图片功能，使用uploadify插件
     */   
    public function ajaxuploadimage()
    {
        $upload = model('UploadImage');
        $res = $upload->imageUpload();
        if($res === false){
            return show(0, '上传失败', '');
        } else {
            return show(1, '上传成功', $res);
        }
    }
    
    /**
     * 定义编辑器方法，实现图片异步上传
     */
    public function kindupload()
    {
        $upload = model('UploadImage');
        $res = $upload->upload();
        
        if($res === false){
            return showKind(1, '上传失败');
        } else {
            return showKind(0, $res);
        }
    }
}