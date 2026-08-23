<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Datos del establecimiento — usados en contratos PDF
    |--------------------------------------------------------------------------
    | Configura estas variables en el .env para no tocar código al cambiarlas.
    |
    | CASONA_ESTABLISHMENT="Palapa \"La Casona\""
    | CASONA_REPRESENTATIVE="María Magdalena Cruz García"
    | CASONA_CITY="San Pedro Pochutla, Oaxaca"
    */

    'establishment'  => env('CASONA_ESTABLISHMENT', 'Palapa "La Casona"'),
    'representative' => env('CASONA_REPRESENTATIVE', 'María Magdalena Cruz García'),
    'city'           => env('CASONA_CITY', 'San Pedro Pochutla, Oaxaca'),

];