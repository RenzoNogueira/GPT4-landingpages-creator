<?php

// echo json_encode(array('tokens' => 30, 'limite' => 30));

// Verifica se existe o cookie nTokens
if (isset($_COOKIE['nTokens'])) {
    // Pega o número de tokens do usuário dos cookies
    $nTokens = $_COOKIE['nTokens'];
    $limite = $_COOKIE['limite'];
} else {
    // Se não existir, cria o cookie com o número de tokens padrão
    $nTokens = 30;
    $limite = 30;
    setcookie('nTokens', $nTokens, time() + (86400 * 30), '/');
    setcookie('limite', $limite, time() + (86400 * 30), '/');
}

// Adiciona um token ao usuário
// setcookie('nTokens', 30, time() + (86400 * 30), '/');

// Retorna o número de tokens do usuário em formato JSON
echo json_encode(array('tokens' => $nTokens, 'limite' => $limite));