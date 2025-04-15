<?php

return [
    'private_key' => env('JWT_PRIVATE_KEY'), // Cargar la clave privada desde .env
    'public_key' => env('JWT_PUBLIC_KEY'),  // Cargar la clave pública desde .env
];
