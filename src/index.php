<?php
require '../vendor/autoload.php';

use \OpenAI as OpenAI;
use \GuzzleHttp\Client;

$chave = file_get_contents('../key.txt');

$client = OpenAI::client($chave);

if (isset($_POST['input'])) {
    $text = json_decode($_POST['input']);
    $stream = $client->chat()->createStreamed([
        'model' => 'gpt-4',
        'messages' => [
            [
                'role' => 'system', 'content' => <<<TEXT
                Olá! Eu sou o ChatGPT Quatro e posso gerar código HTML, jQuery e Bootstrap 4 para criar uma landing page com base nas informações que você me fornecer. Por favor, me dê uma descrição da sua landing page, incluindo informações sobre o navbar, seções de conteúdo e qualquer funcionalidade interativa que você gostaria de adicionar. Com base nessas informações, eu vou gerar o código para a sua página. Se você tiver alguma dúvida ou precisar de ajuda, por favor, me avise. Meu objetivo é gerar um código preciso e funcional para sua landing page. Eu não vou adiconar nenhum tipo de comentário da minha parte, sendo o meu retorno puro código.
TEXT
            ],
            ['role' => 'user', 'content' => $text],
        ],
    ]);

    // Caso o arquivo exista, limpa o arquivo
    if (file_exists('../pages/index.html')) {
        $file = fopen('../pages/index.html', 'w');
        fwrite($file, '');
        fclose($file);
    }

    $file = fopen('../pages/index.html', 'w');

    foreach ($stream as $response) {
        if (isset($response->toArray()['choices'][0]['delta']['content'])) {
            $content = $response->toArray()['choices'][0]['delta']['content'];
            fwrite($file, $content);
        }
    }
    fclose($file);
    // Verifica se o arquivo foi criado
    if (file_exists('pages/' . time() . '.html')) {
        echo 'Arquivo criado com sucesso!';
    } else {
        echo 'Erro ao criar o arquivo!';
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        textarea {
            width: 100%;
            height: 200px;
            margin-bottom: 10px;
            padding: 10px;
        }

        iframe {
            width: 100%;
            height: 500px;
            border: none;
            margin-top: 10px;
        }

        #btn {
            padding: 10px;
            background-color: #000;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div>
        <textarea id="input" cols="30" rows="10">
    Recebemos uma descrição de uma loja de roupas femininas chamada "Formosa" e gostaríamos que você criasse uma landing page em HTML usando jQuery e Bootstrap 4. Aqui está a descrição completa:

A landing page deve ter um navbar na cor vinho, com o nome da loja "Formosa" no centro. O navbar deve ter alguns links padrão, como "Home", "Sobre nós", "Contato" e "Carrinho", e ao passar o mouse sobre esses links, deve haver uma animação.

A seção inicial da página deve ter uma imagem de destaque de uma modelo vestindo uma das roupas da loja, com um botão "Compre agora" que leva os usuários para a página de produtos.

A seção de produtos deve ter uma grade de produtos com imagens, nomes e preços. Ao clicar em um produto, deve aparecer uma janela modal com mais detalhes sobre o produto, incluindo imagens adicionais e um botão "Adicionar ao carrinho".

A seção de contato deve ter um formulário de contato simples para que os usuários possam enviar uma mensagem para a loja.

Por favor, use HTML, jQuery e Bootstrap 4 para criar a página. Se tiver alguma dúvida ou precisar de mais informações, fique à vontade para perguntar.
    </textarea>
        <button id="btn">Enviar</button> <span id="status"></span>
    </div>

    <iframe src="#" style="display: none;"></iframe>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $.fn.renderIframe = function() {
                const iframe = $('iframe');
                iframe.attr('src', '../pages/index.html');
                iframe.show();
                // Atualiza o iframe
                iframe.load(function() {
                    iframe.contents().find('body').html(iframe.contents().find('body').html());
                });
            }
            $('#btn').click(function() {
                const input = $('#input').val();
                console.log(input);
                $result = $.post('index.php', {
                    input: JSON.stringify(input)
                }, function(data) {
                    console.log(data);
                });

                // Atualiza o iframe com o resultado a cada 5 segundos
                setInterval(function() {
                    $.fn.renderIframe();
                }, 5000);

            });
        });
    </script>
</body>

</html>