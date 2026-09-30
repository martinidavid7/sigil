<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ambiente de demonstração
    |--------------------------------------------------------------------------
    |
    | Quando ativo, a tela de login mostra as credenciais do usuário criado
    | pelo DemoSeeder, com um botão para preencher o formulário.
    |
    */

    'demo' => [
        'enabled' => (bool) env('SIGIL_DEMO', false),
        'email' => 'demo@sigil.test',
        'password' => 'password',
    ],

];
