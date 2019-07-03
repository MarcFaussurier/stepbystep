This project aims to be a minimalist web framework with simple features. Suitable for PWA, Cordova, Electron... 

It aims to be a very customizable CMS and Framework that encourage code reuse.

EXAMPLE OF CONTROLLER ::
  ```php 
    /**
      * @param           $request
      * @param           $response
      * @param           $matches  array
      * @return          bool
      * @this_one        request
      * @packet_type     request
      * @match           /hello[a-zA-Z.]{0,5}
      * @priority        0
      * @handle this methods after target ones
      *      ✅💾 🎯 \CloudsDotEarth\StepByStep\Controller::toto
      **/
     public function request(&$request, &$response, &$cross_call_memory, &$main_bundle, &$matches = []): bool {
         $response->end($main_bundle->twig->render("marcfsr/stepbystep/home", ['msg' => 'toto']));
         echo "toto1" . PHP_EOL;
         var_dump($matches);
         return false;
     }```



- High connexion/sec & RAM & latency performances, built around Swoole framework instead of traditional LAMP.

- Strong controllers, use the same comment based declaration syntax even for websocket. Also controllers are chainable and comparable to middlewares 

- TODO : isomorphic twig views, no more page refresh with local cache system and updater

- TODO : webpack hmr support

- TODO : optional isomorphic rust components (thanks to php-ext-wasm)

Write easily your own common tools & management area for all your projects. 


- TODO : Automatic admin panel, because everyone needs it.

- TODO : Works without cookies and also without javascript 
