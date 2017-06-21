<?php
namespace app\common\model;
use think\File;
use think\Model;
use think\Request;

class UploadImage extends Model
{
    /**
     * 上传文件操作
     * @return string|boolean
     */
    public function imageUpload()
    {
        $file = request()->file('file');
        $info = $file->move('public/upload');
        
        if($info && $info->getPathname()){
            //getPathname() 返回move('值')/filename;
            return '/'.$info->getPathname();
        } else {
            return false;
        }
    }
    
    /**
     * 编辑器文件上传
     */
    public function upload()
    {
        //可通过print_r($FILE)获取file('name')的值；
        $file = request()->file('imgFile');
        $info = $file->move('public/upload');
        if($info && $info->getPathname()){
            //getPathname() 返回upload/filename;
            return '/'.$info->getPathname();
        } else {
            return false;
        }
    }
}