<?php 

namespace Phlatson;

echo $view->render('/layouts/default', [
    'output' => $view->render('/partials/articles',[
        'page_number' => $request->get->page ?? 1
    ]),    
]);
