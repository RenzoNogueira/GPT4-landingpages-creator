<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT-4 create Landing page</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.5/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.58.3/codemirror.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.58.3/theme/darcula.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
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

        /* coloca o Fundo iframe escuro */
        #view-page {
            background-color: #000 !important;
        }
    </style>
</head>

<body>
    <main class="p-8">
        <div class="flex justify-end mb-4">
            <div id="user-button"></div>
        </div>

        <div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Descrição da sua Landing page</span>
                    <span class="label-text-alt" id="n-caracteres">0/6000</span>
                </label>
                <textarea id="description" class="textarea textarea-bordered h-24" placeholder="Descreva sua Uma Landing page simples com fundo verde e um título azul Hello World ao centro." id="input"></textarea>
                <progress class="progress w-100 mt-1" style="display: none;" id="progress"></progress>
                <label class="label">
                    <span class="label-text-alt" id="msg-info"></span>
                    <span class="label-text-alt" id="tokens-for-create"><i class="fas fa-info-circle"></i> <span id="n-tokens" class="text-yellow">30/30</span> tokens para criar sua Landing page</span>
                </label>
            </div>
            <div class="my-2">
                <button id="btn-enviar" class="btn btn-primary text-white">Enviar</button>
                <!-- Botao de download -->
                <a id="btn-download" class="btn btn-success text-white ml-2" download="#" style="display: none;">Download</a>
            </div>
        </div>

        <iframe class="rounded" id="view-page" src="#" style="display: none;"></iframe>

        <!-- Botão para atualizar o iframe -->
        <button id="btn-refresh" class="btn btn-primary text-white mt-2" style="display: none;">Atualizar visualização</button>

        <!-- Área para feedback da geração da Landing page -->
        <textarea id="feedback" class="textarea textarea-bordered h-24 mt-2" style="display: none;" disabled></textarea>

        <!-- Código gerado -->
        <div class="my-2" id="code-generated" style="display: none;">
            <h2 class="text-2xl font-bold">Código gerado</h2>
            <div class="form-control mt-2">
                <textarea id="editor" class="textarea textarea-bordered h-24"></textarea>
            </div>
        </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://kit.fontawesome.com/274af9ab8f.js" crossorigin="anonymous"></script>
    <script async crossorigin="anonymous" data-clerk-publishable-key="#" onload="window.Clerk.load()" src="https://renewed-gator-70.clerk.accounts.dev/npm/@clerk/clerk-js@4/dist/clerk.browser.js" type="text/javascript">
    </script>

    <!-- Escript module -->
    <script>
        var nTokens = 30;
        var limiteTokens = 30;

        $(document).ready(function() { // Atualiza o iframe

            // Inicializa o editor
            var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
                mode: {
                    name: "htmlmixed"
                },
                theme: "darcula",
                lineNumbers: true,
                indentUnit: 2,
                tabSize: 2,
                lineWrapping: true
            });

            $.fn.renderIframe = function(renderIframe) {
                const iframe = $('#view-page');
                iframe.attr('src', '../pages/' + renderIframe + '.html');
                iframe.show();
                // Atualiza o conteúdo do iframe
                try {
                    iframe.contents().find('body').html('');
                    iframe.load(function() {
                        $(this).contents().find('body').css('background-color', '#000');
                    });
                } catch (error) {}
            }

            // Função observadora do textarea
            $.fn.observerDescription = function(element) {
                const caracteres = element.val().length;
                $('#n-caracteres').text(`${caracteres}/${limite}`);
                // Verifica se o número de caracteres é maior que 6000
                if (caracteres > limite) {
                    $('#msg-info').text('O número máximo de caracteres foi atingido');
                    // Desabilita o botão de enviar
                    $('#btn-enviar').attr('disabled', true);
                    // Remove o texto digitado a partir do 6000º caractere
                    $(this).val($(this).val().substring(0, limite));
                } else {
                    $('#msg-info').text('');
                    // Habilita o botão de enviar
                    $('#btn-enviar').attr('disabled', false);
                }

                // Muda a cor do texto de limite de caracteres de acordo com a porcentagem de caracteres digitados, começando de verde até vermelho em 0% e 100% respectivamente
                if (caracteres > 0 && caracteres <= (limite * 0.25)) {
                    $('#n-caracteres').css('color', 'green');
                } else if (caracteres > (limite * 0.25) && caracteres <= (limite * 0.5)) {
                    $('#n-caracteres').css('color', 'yellow');
                } else if (caracteres > (limite * 0.5) && caracteres <= (limite * 0.75)) {
                    $('#n-caracteres').css('color', 'orange');
                } else if (caracteres > (limite * 0.75) && caracteres <= limite) {
                    $('.#n-caracteres').css('color', 'red');
                }
            }

            // Carregar o código no editor
            $.fn.loadCode = function(fileName) {
                $.get('../pages/' + fileName + '.html', function(data) {
                    editor.setValue(data);
                });
            }

            // Atualiza o número de tokens
            $.fn.updateTokens = function(nTokens) {
                // Verificab se é menor que 0
                if (nTokens < 0) nTokens = 0;
                $('#n-tokens').text(`${nTokens}/${limiteTokens}`);
                if (nTokens <= 0) {
                    $('#n-tokens').css('color', 'red');
                    $('#btn-enviar').attr('disabled', true);
                } else {
                    $('#n-tokens').css('color', 'yellow');
                    $('#btn-enviar').attr('disabled', false);
                }
            }

            // Busca a quantidade de tokens do usuário
            $.fn.getTokens = function() {
                $.get('../requests/tokens.php', function(data) {
                    data = JSON.parse(data);
                    nTokens = data.tokens;
                    limiteTokens = data.limite;
                    $.fn.updateTokens(nTokens);
                });
            }

            $(this).getTokens();

            // Contagem de caracteres
            const limite = 6000;
            const elementDescription = $('#description');
            $(elementDescription).val('Uma Landing page simples com fundo cinza e um título azul Hello World ao centro.');
            $(elementDescription).observerDescription($(this));
            $(elementDescription).keyup(function() {
                $(this).observerDescription($(this));
            });


            $('#btn-enviar').click(function() {
                const description = $('#description').val();
                // Nome único do arquivo
                const fileName = Math.random().toString(36).substring(7);

                // Adiciona a mensagem de carregamento
                $('#msg-info').text('Criando sua Landing page, aguarde...');
                $('#msg-info').toggleClass('text-yellow-500');
                $(this).toggleClass('loading');
                $(this).attr('disabled', true);
                // Muda o texto do botão enviar para "Criando..."
                $(this).text('Criando...');

                // Mostra o progress
                $('#progress').show();

                $.post('../requests/request.php', { // Inicia a requisição
                    request: {
                        description: description,
                        fileName: fileName
                    }
                }, function(data) {
                    data = JSON.parse(data);
                    if (data.status == 'error') {
                        $('#msg-info').text(data.message);
                        $('#msg-info').toggleClass('text-yellow-500 text-red-500');
                        $('#btn-enviar').text('Enviar');
                        $('#btn-enviar').toggleClass('loading');
                        $('#btn-enviar').attr('disabled', false);
                        return;
                    } else {
                        // Atualiza a mensagem de carregamento
                        $('#msg-info').text('Sua Landing page foi criada com sucesso!');
                        // Define a cor da mensagem de carregamento
                        $('#msg-info').toggleClass('text-yellow-500 text-lime-500');

                        // Botão de download
                        $('#btn-download').show();
                        $('#btn-download').attr('href', `../pages/${fileName}.html`);

                        // Botão de atualizar
                        $('#btn-refresh').show();

                        // Botão de enviar
                        $('#btn-enviar').text('Recriar');
                        $('#btn-enviar').toggleClass('loading');
                        $('#btn-enviar').attr('disabled', false);

                        // progress
                        $('#progress').hide();

                        // Atualiza o iframe com o resultado
                        $(this).renderIframe(fileName);
                        // Para o intervalo de tempo
                        clearInterval(intervalId);
                        $(this).updateTokens(data.tokens); // Atualiza o número de tokens

                        // Carrega a mensagem de feddback
                        $('#feedback').show();
                        $('#feedback').val(data.explication);

                        // Carrega o código no editor
                        $(this).loadCode(fileName);
                        $("#code-generated").show();
                    }
                });

                // Adiciona o evento no botão de atualizar o iframe
                $('#btn-refresh').click(function() {
                    $.fn.renderIframe(fileName);
                });


                // Inicia o intervalo de tempo
                var intervalId = setInterval(function() {
                    // Atualiza o iframe com o resultado
                    $(this).renderIframe(fileName);
                }, 5000);

            });

            // Mount the sign in component inside the HTML element
            // with id "sign-in".
            setTimeout(function() {
                const Clerk = window.Clerk;

                // Clerk.mountSignIn(
                //     document.getElementById("sign-in")
                // );

                // // Open the sign in component as a modal.
                // window.Clerk.openSignIn();
                setInterval(function() {
                    if (!window.Clerk.user) {
                        window.Clerk.openSignIn();
                    }
                }, 1000);

                const el = document.getElementById("user-button");
                // Mount the pre-built Clerk UserProfile component
                // in an HTMLElement on your page. 
                window.Clerk.mountUserButton(el);

                console.log(window.Clerk.user);

            }, 1000);

        });
    </script>
</body>

</html>