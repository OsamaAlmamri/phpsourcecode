<?php
/* 多语言路由 */
$router->group( ['prefix' => 'language'], function ($router)
{
    $router->get( 'ajaxIndex', 'LanguageController@ajaxIndex' )->name( 'language.ajaxIndex' );
    $router->get( '{id}/status/{status}', 'LanguageController@status' );
    $router->get( '{id}/invite', 'LanguageController@invite' )->name( 'language.invite' );
    $router->post( '{id}/invite', 'LanguageController@storeInviteUser' );
    $router->get( '{id}/send', 'LanguageController@sendEmail' );
    $router->get( 'count_completion_status/{project_id}', 'LanguageController@countCompletionStatus' )->name( 'language.completion' );

    /* 下载翻译 */
    $router->get( '{id}/download', 'LanguageController@download' )->name( 'language.download' );
    $router->get( '{id}/download/{method}', 'LanguageController@downloadOutput' )->name( 'language.output' );
    /* 导出 js 代码 For 柱哥 */
    $router->get( 'export_js/{id}', 'LanguageController@exportJs' )->name( 'language.export_js' );
    $router->get( 'export_excel/{id}/{translatedStatus}', 'LanguageController@exportExcel' )->name( 'language.export_excel' );
});
$router->resource( 'language', 'LanguageController' );