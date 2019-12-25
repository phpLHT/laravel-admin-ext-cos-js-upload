<?php

namespace Encore\CosJsUpload;

use Illuminate\Support\ServiceProvider;
use Encore\Admin\Admin;

class CosJsUploadServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(CosJsUpload $extension)
    {
        if (! CosJsUpload::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'cos-js-upload');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/cos-js-upload')],
                'cos-js-upload'
            );
        }

        Admin::booted(function(){
            Admin::js('/vendor/laravel-admin-ext/cos-js-upload/cos-js-sdk-v5.min.js');
            Admin::js('/vendor/laravel-admin-ext/cos-js-upload/bootstrap.file-input.js');
        });
        
        $this->app->booted(function () {
            CosJsUpload::routes(__DIR__.'/../routes/web.php');
        });
    }
}