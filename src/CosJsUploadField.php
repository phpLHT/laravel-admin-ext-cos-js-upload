<?php
namespace Encore\CosJsUpload;
use Encore\Admin\Form\Field;

class CosJsUploadField extends Field
{
    public $view = 'cos-js-upload::index';
    
    protected $group = 'bigfile';
    
    public function group($group)
    {
        $this->group = $group;
        return $this;
    }
    public function render()
    {
        $name = $this->formatName($this->column);
        $route_prefix=config('admin.route.prefix');
        $bucket=env('QCLOUD_COS_DEFAULT_BUCKET');
        $region=env('QCLOUD_COS_REGION');
        $this->script = <<<SRC
        $('#{$name}-resource').bootstrapFileInput();
        var Bucket = '{$bucket}';
        var Region = '{$region}';
        var output= $('#{$name}-output');
        var progressbar=$('#{$name}-progressbar');
        var savedpath=$('#{$name}-savedpath');
        
        // 初始化实例
        var cos = new COS({
            getAuthorization: function (options, callback) {
                var url = '/{$route_prefix}/cos-js-upload';
                var xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.onload = function (e) {
                    try {
                        var data = JSON.parse(e.target.responseText);
                    } catch (e) {
                    }
                    callback({
                        TmpSecretId: data.credentials && data.credentials.tmpSecretId,
                        TmpSecretKey: data.credentials && data.credentials.tmpSecretKey,
                        XCosSecurityToken: data.credentials && data.credentials.sessionToken,
                        ExpiredTime: data.expiredTime,
                    });
                };
                xhr.send();
            }
        });

        // 监听选文件
        document.getElementById('{$name}-resource').onchange = function () {

            var file = this.files[0];
            if (!file) return;

            // 分片上传文件
            cos.sliceUploadFile({
                Bucket: Bucket,
                Region: Region,
                Key: '{$this->group}/'+file.name,
                Body: file,
                onHashProgress: function (progressData) {
                    output.text('校验中'+progressData.percent*100+'%');
                    progressbar.css('width', progressData.percent*100+'%');
                },
                onProgress: function (progressData) {
                    output.text('上传中'+progressData.percent*100+'%');
                    progressbar.css('width', progressData.percent*100+'%');
                },
            }, function (err, data) {
                if(err===null && data.statusCode==200){
                    output.text('上传完成');
                    savedpath.val(data.Key);
                }
            });

        };
       
SRC;
        return parent::render();
    }
}
