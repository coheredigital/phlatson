<?php 

namespace Phlatson;


$subPageTitle = md5(date("U"));
$subPage = new Page($subPageTitle);


echo $this->render('/layouts/default', [
    'output' => $this->render('/partials/articles',[
        'page_number' => $request->get->page ?? 1
    ]),    
]);
