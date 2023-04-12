<?php
require '../vendor/autoload.php';

use \OpenAI as OpenAI;
use \GuzzleHttp\Client;

$chave = file_get_contents('../key.txt');

$client = OpenAI::client($chave);

if (isset($_POST['request'])) {
    // $text = json_decode($_POST['input']);
    // $fileName = json_decode($_POST['fileName']);
    $request = $_POST['request'];
    $text = $request['description'];
    $fileName = $request['fileName'];
    $stream = $client->chat()->createStreamed([
        'model' => 'gpt-4',
        'messages' => [
            [
                'role' => 'system', 'content' => <<<TEXT
                Posso gerar código HTML, jQuery e Bootstrap 4 para criar uma landing page com base nas informações que você me fornecer.
                Receberei uma descrição da sua landing page, incluindo informações sobre o navbar, seções de conteúdo e qualquer funcionalidade interativa que você gostaria de adicionar.
                Com base nessas informações, eu vou gerar o código para a sua página.
                Meu objetivo é gerar um código preciso e funcional para sua landing page. esse código será salvo em um arquivo HTML e você poderá baixá-lo. O meu retorno para a sua descrição será um código HTML, jQuery e Bootstrap 4.

                Exemplo de descrição:

                Uma Landing page simples com fundo cinza, um título azul "Hello World" ao centro da tela e uma imagem centralizada abaixo. Utilize explicitamente as classes do Bootstrap 4 e evite o uso de CSS.

                Saída:

                ```
                <!DOCTYPE html>
                <html lang="pt-br">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.">
                        <title>Landing Page</title>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
                        <style>
                            body {
                                width: 100vw !important;
                                height: 100vh !important;
                                margin: 0px !important;
                                padding: 0px !important;
                            }
                        </style>
                    </head>
                    <body class="bg-light d-flex justify-content-center align-items-center">

                        <main class="d-flex flex-column justify-content-center align-items-center">
                            <h1 class="text-primary">Hello World</h1>
                            <h2 class="text-secondary">Sample Text</h2>
                            <img src="https://picsum.photos/###/###" class="rounded mx-auto d-block mt-4" alt="Exemplo de imagem">
                            <button type="button" class="btn btn-primary btn-lg btn-block mt-4">Examplo de botão</button>
                        </main>

                        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
                        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3ttrags/dist/umd/popper.min.js" crossorigin="anonymous"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
                    </body>
                </html>
                ```
    TEXT
            ],
            ['role' => 'user', 'content' => $text],
        ],
    ]);

    // Cerifica se a pasta ../pages/ existe, caso não exista, cria a pasta
    if (!file_exists('../pages/')) {
        mkdir('../pages/', 0777, true);
    }

    // Caso o arquivo exista, limpa o arquivo
    if (file_exists('../pages/' . $fileName . '.html')) {
        $file = fopen('../pages/' . $fileName . '.html', 'w');
        fwrite($file, '');
        fclose($file);
    }

    $file = fopen('../pages/' . $fileName . '.html', 'a');

    foreach ($stream as $response) {
        if (isset($response->toArray()['choices'][0]['delta']['content'])) {
            $content = $response->toArray()['choices'][0]['delta']['content'];
            fwrite($file, $content);
        }
    }
    fclose($file);
    // Verifica se o arquivo foi criado
    if (file_exists('../pages/' . $fileName . '.html')) {
        echo json_encode(['status' => 'success', 'fileName' => $fileName . '.html']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit;
}
?>