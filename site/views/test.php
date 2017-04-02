<?php
echo memory_get_usage(true);

// add ref for debugging, remove later
require ROOT_PATH . "libraries/ref/ref.php";
ref::config('expLvl', 2);
ref::config('validHtml', true);

$data = file_get_contents('C:\Users\Adam\Websites\dev\flatbed\MOCK_DATA.json');
$data = json_decode($data);


foreach ($data as $key => $value) {

    $name = Filter::name($value->title);
    $parent = $pages->get("/news/");


    $article = $pages->new( $name );
    $article->parent = $parent;

    $article->template = "article";
    $article->title = $value->title;
    $article->content = $value->content;

    $article->save();

    r( $article->name );
    r( $article->getPath() );
    r( $article );

    if ( $key == 0 ) break;

}
