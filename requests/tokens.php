<?php

require_once "../.config.php";

if (isset($_POST["user_id"])) {

    $user_id = $_POST["user_id"];

    $ch = curl_init();
    $url = "https://api.clerk.com/v1";
    $secretKey = CLERK_SECRET_KEY;
    // Bearer Token
    $security = "Bearer " . $secretKey;
    // Body
    $body = [
        "public_metadata" => json_decode("{}"),
        "private_metadata" => json_decode("{}"),
        "unsafe_metadata" => json_decode("{}"),
    ];

    $params = [
        CURLOPT_URL => $url . "/users/" . $user_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: $security",
            "Content-Type: application/json",
        ],
        // CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_CUSTOMREQUEST => "GET",
    ];

    // Define as opções do cURL
    curl_setopt_array($ch, $params);

    // Faz a requisição e retorna o resultado
    $result = curl_exec($ch);

    // Fecha a conexão
    curl_close($ch);

    // Decodifica o resultado JSON
    $result = json_decode($result);

    // Verifica se ocorreu algum erro
    if (isset($result->error)) {
        // Se ocorreu, define a mensagem de erro
        $mensageError = $result->error->message;
    } else {
        // Se não ocorreu, define a mensagem de sucesso
        $mensageError = "Sucesso!";
    }

    setcookie('nTokens', $result->public_metadata->tokens, time() + (86400 * 30), '/');
    setcookie('user_id', $result->id, time() + (86400 * 30), '/');

    echo json_encode([
        "result" => $result->public_metadata,
        "error" => $mensageError,
    ]);
} else {
    echo json_encode([
        "error" => "user_id não informado",
    ]);
}
