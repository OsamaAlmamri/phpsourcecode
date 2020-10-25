<?php
/* 项目管理路由 */
$router->group( ['prefix' => 'project'], function ($router)
{
    $router->get( 'ajaxIndex', 'ProjectController@ajaxIndex' )->name( 'project.ajaxIndex' );
    $router->get( '{id}/input', 'ProjectController@input' )->name( 'key.input' );
    $router->post( '{id}/input', 'ProjectController@storeKey' );
    $router->delete( '{id}/input', 'ProjectController@deleteKey' );
    $router->any( '{id}/import', 'ProjectController@importiOS' )->name( 'project.import' );
    $router->post( 'key/sort', 'ProjectController@sortKey' );
    $router->post( 'tag_change', 'ProjectController@tagChange' );
    $router->post( 'length_change', 'ProjectController@lengthChange' );
    $router->any( 'import_excel/{id}', 'ProjectController@importExcel' )->name( 'import.excel' );
});
$router->resource( 'project', 'ProjectController' );