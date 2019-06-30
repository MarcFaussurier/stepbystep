This project aims to be a minimalist web framework with simple features. Suitable for PWA, Cordova, Electron... 

- High performances, built around Swoole framework.

- isomorphic twig views, no more page refresh with cache system, updater and service workers.

- webpack hmr support

- optional isomorphic rust codes (thanks to php-ext-wasm)

- No model, ORM or shit, instead we use plain PostgreSQL and its function (stored procedures) system.

- Bundle system, share assets and controllers between your projects. Just reuse what you did / you want in your other projects.
Write easily your own common tools & management area for all your projects. 

- Strong controllers, use the same comment based declaration syntax even for websocket. Also controllers are chainable and comparable to middlewares 

- Automatic admin panel, because everyone needs it.

- Works without cookies and also without javascript 