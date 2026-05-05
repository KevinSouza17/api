<?php

    //Cabeçalho
    header("Content-Type: application/json");  //Define o tipo de resposta

    $metodo = $_SERVER['REQUEST_METHOD'];

    //Recupera o arquivo JSON na mesma pasta do projeto
    $arquivo = 'usuarios.json';

    //Verifica se o arquivo existe, se não existir, cria um array vazio
    if (!file_exists($arquivo)) {
        file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    //Lê o conteúdo do arquivo JSON
    $usuarios = json_decode(file_get_contents($arquivo), true);

    //conteúdo
        $usuarios = [
            ["id" => 1, "nome" => "Astrid", "email" => "stormfly@gmail.com"],
            ["id" => 2, "nome" => "Soluço", "email" => "toothless@gmail.com"]
        ];



    //echo "Método de requisição: ". $metodo;
    
    switch ($metodo) {
        case 'GET':
            echo json_encode($usuarios);
            echo json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        case 'POST':
            //echo "Aqui as ações do método POST";
            $dados = json_decode(file_get_contents('php://input'), true);
            // print_r($dados);
            if (!isset($dados["id"])) || !isset($dados["nome"]) || !isset($dados["email"])) {
                http_response_code(400); 
                echo json_encode(["erro" => "Dados incompletos."], JSON_UNESCAPED_UNICODE);
                exit;
            }

               $newUser = [
                "id" => $dados["id"],
                "nome" => $dados["nome"],
                "email" => $dados["email"],
            ];

            //Adiciona o novo usuário ao array existente
            $usuarios[] = $newUser;
                file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                echo json_encode(["mensagem" => "Usuário adicionado com sucesso!","usuarios" => $usuarios], JSON_UNESCAPED_UNICODE);
            break;

        default:
            http_response_code(405); // Método não permitido
            echo json_encode(["erro" => "Método não permitido."], JSON_UNESCAPED_UNICODE);
            break;
    }

?>