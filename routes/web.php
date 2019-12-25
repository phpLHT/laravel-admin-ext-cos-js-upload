<?php

use Encore\CosJsUpload\Http\Controllers\CosJsUploadController;

Route::get('cos-js-upload', CosJsUploadController::class.'@index');