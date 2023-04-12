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

                Simples landing page.

                Saída:

                ```
                <!DOCTYPE html>
                <html lang="pt-br">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.">
                        <title>Title</title>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                    </head>
                    <body>
                        <div class="container">
                            <h1>My First Bootstrap Page</h1>
                            <p>This is some text.</p>
                        </div>
                        <img src="https://picsum.photos/200/300" class="img-fluid" alt="Responsive image">

                        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
                        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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