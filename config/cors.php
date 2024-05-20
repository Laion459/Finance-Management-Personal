<?php

return [
    'paths' => ['api/*'], // Aplicar CORS apenas a rotas que começam com 'api/'
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:8000'], // Substitua pelo seu domínio de frontend
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
