<?php
require '../vendor/autoload.php';
require '../.config.php';

use \OpenAI as OpenAI;
use \GuzzleHttp\Client;

$chave = file_get_contents('../key.txt');

$client = OpenAI::client($chave);

if (isset($_POST['request'])) {
    $request = $_POST['request'];
    $text = $request['description'];
    $fileName = $request['fileName'];
    $user_id = $request['user_id'];
    $stream = $client->chat()->createStreamed([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'system', 'content' => <<<TEXT
                Posso gerar código HTML, jQuery e Bootstrap 4 e Vue js 2 para criar uma landing page com base nas informações que você me fornecer.
                Receberei uma descrição da sua landing page, incluindo informações sobre o navbar, seções de conteúdo e qualquer funcionalidade interativa que você gostaria de adicionar.
                Com base nessas informações, eu vou gerar o código para a sua página.

                Meu objetivo é gerar um código preciso e funcional para sua landing page. esse código será salvo em um arquivo HTML e você poderá baixá-lo. O meu retorno para a sua descrição será um código HTML, jQuery, Vue js 2, Bootstrap 4 e ícones do Bootstrap. As interações do site serão totalmente em jQuery e Vue js 2. Cores no CSS sempre irei pôr em formato HEX.

                Toda página que eu criar será uma singler page aplicatrion, embora possa ter outras páginas além da princopal. Irei sempre começar a escrever a minha resposta com "```html" e finalizar com "```". Eu não preciso explicar ou comentar nada sobre o código senão dará erro eo executar o HTML. Então não terá texto antes de "```html" e depois de "```". O que estiver entre "{{" e "}}" será substituído via script.

                Exemplo de descrição:

                Uma Landing page simples com fundo cinza, um título azul "Hello World" ao centro da tela e uma imagem centralizada abaixo. Utilize explicitamente as classes do Bootstrap 4 e evite o uso de CSS.

                Exemplo de saída, não vou explicar nada antes de dizer o código:

                ```html
                <!DOCTYPE html>
                <html lang="pt-br">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.">
                    <title>Landing Page</title>
                    <link rel="stylesheet" href="{{bootstrap-css}}" crossorigin="anonymous" referrerpolicy="no-referrer" />
                    <link rel="stylesheet" href="{{bootstrap-icons}}" crossorigin="anonymous" referrerpolicy="no-referrer" />
                </head>

                <body class="bg-secondary" id="app">
                    <div id="app">
                        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                            <a class="navbar-brand" href="#">Logo</a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">About</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Contact</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>

                        <main class="container-fluid mt-2">
                            <div class="jumbotron">
                                <h1 class="display-4 text-center">Hello World</h1>
                                <hr class="my-4">
                                <p class="lead text-center">This is a simple hero unit, a simple jumbotron-style component for calling extra
                                    attention to featured content or information.</p>
                                <div class="d-flex justify-content-center">
                                    <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
                                </div>
                            </div>

                            <div class="card-deck">
                                <div class="card">
                                    <img class="card-img-top" src="https://picsum.photos/500/300" alt="Card image cap">
                                    <div class="card-body">
                                        <h5 class="card-title">Card title</h5>
                                        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to
                                            additional content. This content is a little bit longer.</p>
                                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                    </div>
                                </div>
                                <div class="card">
                                    <img class="card-img-top" src="https://picsum.photos/500/300" alt="Card image cap">
                                    <div class="card-body">
                                        <h5 class="card-title">Card title</h5>
                                        <p class="card-text">This card has supporting text below as a natural lead-in to additional content.
                                        </p>
                                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                    </div>
                                </div>
                            </div>
                        </main>
                    </div>

                    <script src="{{jquery}}" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                    <script src="{{popper}}" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                    <script src="{{vue-js-2}}" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                    <script src="{{bootstrap-js}}" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                    <script>
                        // Exemplo de inicialização do Vue js 2, será modificado para cada caso.
                        var app = new Vue({
                            el: '#app',
                            data: {}
                        })
                    </script>
                </body>

                </html>```

                No final da saída, posso explicar como funciona o código gerado. Não preciso mais citar linhas de código, pois já fiz isso no exemplo. Irei citar os elementos que adicionei na criação do arquivo. e seu fluxo de execução e funcionalidade, além se tem como melhorar.
    TEXT
            ],
            ['role' => 'user', 'content' => $text],
        ],
    ]);

    $linkBootstrap = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css';
    $linkBootstrapIcons = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.4/font/bootstrap-icons.min.css';

    $linkJquery = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js';
    $linkPopper = 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js';
    $linkBootstrapJs = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js';
    $linkVueJs2 = 'https://cdn.jsdelivr.net/npm/vue@2';

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
        // Se foi criado pega o conteúdo do arquivo e aproveita apenas o que está dentro da de ```html até ```
        $file = fopen('../pages/' . $fileName . '.html', 'r');
        $content = fread($file, filesize('../pages/' . $fileName . '.html'));
        fclose($file);

        // Substitui as variáveis de CDN pelo valor verdadeiro
        $content = str_replace("{{bootstrap-css}}", $linkBootstrap, $content);
        $content = str_replace("{{bootstrap-icons}}", $linkBootstrapIcons, $content);
        $content = str_replace("{{jquery}}", $linkJquery, $content);
        $content = str_replace("{{popper}}", $linkPopper, $content);
        $content = str_replace("{{bootstrap-js}}", $linkBootstrapJs, $content);
        $content = str_replace("{{vue-js-2}}", $linkVueJs2, $content);

        // Pega o conteúdo que está dentro da de ```html até ```
        $content = explode('```html', $content);
        $content = explode('html```', $content[1]);
        $content = $content[0];
        $content = explode('```', $content);
        $explication = $content[1];
        $content = $content[0];

        // Remove os espaços em branco de início e fim
        $explication = trim($explication);

        // Remove duplas quebras de linha
        $explication = str_replace("\n\n", "\n", $explication);

        // Limpa o arquivo
        $file = fopen('../pages/' . $fileName . '.html', 'w');
        fwrite($file, '');
        fclose($file);
        // Escreve o conteúdo dentro do arquivo
        $file = fopen('../pages/' . $fileName . '.html', 'a');
        fwrite($file, $content);
        fclose($file);

        // Subtrai um token do usuário
        $nToken = getTokens()["tokens"] - 1;

        $updateTokens = saveTokens($nToken, $user_id);

        echo json_encode(['status' => 'success', 'fileName' => $fileName . '.html', 'explication' => $explication, 'tokens' => $nToken, 'updateTokens' => $updateTokens]);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit;
}

function saveTokens($nTokens, $user_id = null)
{
    // Salva o cookie durante 1 Mes
    if ($user_id == null) {
        $user_id = $_COOKIE['user_id'];
    }

    setcookie('nTokens', $nTokens, time() + (86400 * 30), '/'); // 86400 = 1 day

    /**
     * curl -XPATCH -H 'Authorization: Bearer sk_test_woUdRZ6okDZLghasPVUHZRzLMImfXfZvLxIMR1lTF' -H "Content-type: application/json" -d '{
     *  "public_metadata": {
     *    "tokens": 0
     *  }
     *}' 'https://api.clerk.com/v1/users/{user_id}/metadata'
     */

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.clerk.com/v1/users/' . $user_id . '/metadata');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');

    $headers = array(
        'Authorization: Bearer sk_test_woUdRZ6okDZLghasPVUHZRzLMImfXfZvLxIMR1lTFp', // Substitua pelo seu token de autorização
        'Content-Type: application/json'
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $data = array(
        "public_metadata" => array(
            "tokens" => $nTokens
        )
    );
    $data_json = json_encode($data);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Erro:' . curl_error($ch);
    }
    curl_close($ch);

    echo $result;
    exit;
}

function getTokens()
{
    // Verifica se existe o número de tokens do usuário nos cookies
    if (isset($_COOKIE['nTokens'])) {
        if (is_numeric($_COOKIE['nTokens'])) {
            $nTokens = intval($_COOKIE['nTokens']);
        }
    } else {
        $nTokens = 0;
    }
    return array('tokens' => $nTokens);
}
