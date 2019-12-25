laravel-admin extension
======
该扩展基于 腾讯云 cos-js-sdk-v5 和 laravel-admin-extensions/large-file-upload  的页面，开发的大文件上传工具。当前laravel版本Laravel5.5。

1.安装：
    composer require phpLHT/laravel-admin-ext-cos-js-upload

2.发布本资源扩展包的静态资源：

    php artisan vendor:publish php artisan vendor:publish php artisan vendor:publish --provider=Encore\PHPInfo\PHPInfoServiceProvider

3.注册进laravel-admin,在app/Admin/bootstrap.php中添加以下代码：

    Encore\Admin\Form::extend('cosupload',\Encore\CosJsUpload\CosJsUploadField::class);

4.在控制器中直接调用
    
    $form->cosupload('ColumnName','LabelName');

注意：需要使用到 cos 的配置在 .env 中增加如下参数；

    QCLOUD_COS_SECRET_ID=
    QCLOUD_COS_SECRET_KEY=
    QCLOUD_COS_DEFAULT_BUCKET=
    QCLOUD_COS_REGION=

