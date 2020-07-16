<?php 

namespace Phlatson;

echo $this->render('/layouts/default', [
    'output' => $this->render('/partials/articles',[
        'page_number' => $request->get->page ?? 1
    ]),    
]);
