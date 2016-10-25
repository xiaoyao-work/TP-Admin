<?php
$base_domain = C('BASE_DOMAIN', null, 'tp5.hhailuo.com');
$app->group(['domain' => 'admin.' . $base_domain, 'module' => 'Admin'], function () use ($app) {
    // 通配路由组
    $app->any('/(:class(/:method(/:params+)))', ["controller" => "class", "action" => "method"]);
});

$app->group(['domain' => 'api.' . $base_domain, 'module' => 'Api'], function () use ($app) {
    $app->map('/upload', 'File:upload')->via('POST', 'OPTIONS');
    $app->post('/crop', 'File:crop');

    // 通配路由组
    $app->any('/(:class(/:method(/:params+)))', ["controller" => "class", "action" => "method"]);
});

$app->group(['domain' => '{domain}.' . $base_domain, 'module' => 'Home'], function () use ($app) {
    // 资讯
    $app->get('/news', 'Post:index');
    $app->get('/post/:post_type', 'Post:index');
    $app->get('/post/:post_type/:id', 'Post:single');
    $app->get('/taxonomy/:name/:id', 'Post:taxonomy');

    $app->get('/search', 'Post:search');

    $app->get('/getTerms', 'Taxonomy:getTerms');
    $app->get('/guide', 'Index:guide');
    $app->get('/:slug', 'Page:single');

    // 通配路由组
    $app->any('/(:class(/:method(/:params+)))', ["controller" => "class", "action" => "method"]);
});

// 通配路由组
$app->any('/(:group(/:class(/:method(/:params+))))', ["module" => "group", "controller" => "class", "action" => "method"]);