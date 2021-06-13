<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
date_default_timezone_set('America/Sao_Paulo');

// Parâmetros da base de dados
$hostname      = 'localhost';
$username      = 'root';
$password     = '123456';
$database      = 'curso_dev';
$charset      = 'utf8';

// Parâmetros da conexão
$dados     = "mysql:host=" . $hostname . ";port=3306;dbname=" . $database . ";charset=" . $charset;
$options     = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES   => false,
);
// Conexão
$pdo     = new PDO($dados, $username, $password, $options);

$json    =  file_get_contents('php://input');
$obj     =  json_decode($json);
$key     =  strip_tags($obj->key);

switch ($key) {

    case "list_movimentos":

        $data    = array();
        try {
            $sql      = 'SELECT * FROM Movimentos ORDER BY CodMovimento ASC';
            $stmt     =    $pdo->prepare($sql);
            $stmt->execute();
            while ($row  = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }
            echo json_encode($data);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        break;

    case "insert_update_movimentos":

        $movimento = $obj->movimento;
        $data = array();

        try {

            $pdo->beginTransaction();

            if (!empty($movimento->CodMovimento) && $movimento->CodMovimento) {
                $mensagem = 'Movimento atualizado com sucesso!';
                $sql  = "UPDATE Movimentos SET DescMovimento = :DescMovimento, Valor = :Valor, CodReceita = :CodReceita, CodDespesa = :CodDespesa WHERE CodMovimento = '$movimento->CodMovimento'";
            } else {
                $mensagem = 'Movimento cadastrado com sucesso!';
                $sql  = 'INSERT INTO Movimentos(DescMovimento,Valor,CodReceita, CodDespesa) VALUES(:DescMovimento,:Valor,:CodReceita, :CodDespesa)';
            }

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':DescMovimento', $movimento->DescMovimento, PDO::PARAM_STR);
            $stmt->bindParam(':Valor', $movimento->Valor, PDO::PARAM_STR);
            $stmt->bindParam(':CodReceita', $movimento->CodReceita, PDO::PARAM_INT);
            $stmt->bindParam(':CodDespesa', $movimento->CodDespesa, PDO::PARAM_INT);

            $stmt->execute();

            $sql      = 'SELECT * FROM Movimentos ORDER BY CodMovimento ASC';
            $stmt     =    $pdo->prepare($sql);

            $stmt->execute();

            while ($row  = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }

            if ($pdo->commit()) {
                echo json_encode(array(
                    'mensagem' => $mensagem,
                    'dados' => $data,
                    'erro' => ''
                ));
            } else {
                $pdo->rollBack();
                echo json_encode(array(
                    'mensagem' => 'Erro ao cadastrar lançamento!',
                    'dados' => [],
                    'erro' => 'Erro ao cadastrar lançamento!'
                ));
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'mensagem' => 'Erro ao cadastrar lançamento!',
                'dados' => [],
                'erro' => $e->getMessage()
            ));
        }

        break;

    case "delete_movimentos":

        $CodMovimento    =    filter_var($obj->CodMovimento, FILTER_SANITIZE_NUMBER_INT);
        $data    = array();

        try {
            $pdo->beginTransaction();

            $sql     = "DELETE FROM Movimentos WHERE CodMovimento = :CodMovimento";
            $stmt     =    $pdo->prepare($sql);
            $stmt->bindParam(':CodMovimento', $CodMovimento, PDO::PARAM_INT);
            $stmt->execute();

            $sql      = "SELECT * FROM Movimentos ORDER BY CodMovimento ASC";
            $stmt    =    $pdo->prepare($sql);
            $stmt->execute();

            while ($row  = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }

            if ($pdo->commit()) {
                echo json_encode(array(
                    'mensagem' => 'Lançamento excluído com sucesso!',
                    'dados' => $data,
                    'erro' => ''
                ));
            } else {
                $pdo->rollBack();
                echo json_encode(array(
                    'mensagem' => 'Erro ao cadastrar lançamento!',
                    'dados' => [],
                    'erro' => 'Erro ao cadastrar lançamento!'
                ));
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'mensagem' => 'Erro ao cadastrar lançamento!',
                'dados' => [],
                'erro' => $e->getMessage()
            ));
        }
        break;

    case "list_cat_receitas":

        $data    = array();
        try {
            $sql      = 'SELECT * FROM CatReceitas ORDER BY CodReceita ASC';
            $stmt     =    $pdo->prepare($sql);
            $stmt->execute();
            while ($row  = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }
            echo json_encode($data);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        break;

    case "list_cat_despesas":

        $data    = array();
        try {
            $sql      = 'SELECT * FROM CatDespesas ORDER BY CodDespesa ASC';
            $stmt     =    $pdo->prepare($sql);
            $stmt->execute();
            while ($row  = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }
            echo json_encode($data);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        break;

    case "delete_cat_despesas":

        $CodDespesa    =    filter_var($obj->CodDespesa, FILTER_SANITIZE_NUMBER_INT);
        $data    = array();

        try {
            $pdo->beginTransaction();

            $sql     = "DELETE FROM CatDespesas WHERE CodDespesa = :CodDespesa";
            $stmt     =    $pdo->prepare($sql);
            $stmt->bindParam(':CodDespesa', $CodDespesa, PDO::PARAM_INT);
            $stmt->execute();

            $sql      = "SELECT * FROM CatDespesas ORDER BY CodDespesa ASC";
            $stmt    =    $pdo->prepare($sql);
            $stmt->execute();

            while ($row  = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }

            if ($pdo->commit()) {
                echo json_encode(array(
                    'mensagem' => 'Categoria excluída com sucesso!',
                    'dados' => $data,
                    'erro' => ''
                ));
            } else {
                $pdo->rollBack();
                echo json_encode(array(
                    'mensagem' => 'Erro ao excluir categoria!',
                    'dados' => [],
                    'erro' => 'Erro ao excluir categoria!'
                ));
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'mensagem' => 'Erro ao excluir categoria!',
                'dados' => [],
                'erro' => $e->getMessage()
            ));
        }
        break;

    case "delete_cat_receitas":

        $CodReceita    =    filter_var($obj->CodReceita, FILTER_SANITIZE_NUMBER_INT);
        $data    = array();

        try {
            $pdo->beginTransaction();

            $sql     = "DELETE FROM CatReceitas WHERE CodReceita = :CodReceita";
            $stmt     =    $pdo->prepare($sql);
            $stmt->bindParam(':CodReceita', $CodReceita, PDO::PARAM_INT);
            $stmt->execute();

            $sql      = "SELECT * FROM CatReceitas ORDER BY CodReceita ASC";
            $stmt    =    $pdo->prepare($sql);
            $stmt->execute();

            while ($row  = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }

            if ($pdo->commit()) {
                echo json_encode(array(
                    'mensagem' => 'Categoria excluída com sucesso!',
                    'dados' => $data,
                    'erro' => ''
                ));
            } else {
                $pdo->rollBack();
                echo json_encode(array(
                    'mensagem' => 'Erro ao excluir categoria!',
                    'dados' => [],
                    'erro' => 'Erro ao excluir categoria!'
                ));
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'mensagem' => 'Erro ao excluir categoria!',
                'dados' => [],
                'erro' => $e->getMessage()
            ));
        }
        break;

    case "insert_update_cat_despesas":

        $categoria = $obj->categoria;
        $data = array();

        try {

            $pdo->beginTransaction();

            if ($categoria->CodDespesa > 0) {
                $mensagem = 'Categoria despesa atualizada com sucesso!';
                $sql  = "UPDATE CatDespesas SET DescDespesa = :DescDespesa WHERE CodDespesa = '$categoria->CodDespesa'";
            } else {
                $mensagem = 'Categoria despesa cadastrada com sucesso!';
                $sql  = 'INSERT INTO CatDespesas(DescDespesa) VALUES(:DescDespesa)';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':DescDespesa', $categoria->DescDespesa, PDO::PARAM_STR);
            $stmt->execute();

            $sql      = 'SELECT * FROM CatDespesas ORDER BY CodDespesa ASC';
            $stmt     =    $pdo->prepare($sql);

            $stmt->execute();

            while ($row  = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }

            if ($pdo->commit()) {
                echo json_encode(array(
                    'mensagem' => $mensagem,
                    'dados' => $data,
                    'erro' => '',
                    'categoria' => 'D'
                ));
            } else {
                $pdo->rollBack();
                echo json_encode(array(
                    'mensagem' => 'Erro ao cadastrar categoria!',
                    'dados' => [],
                    'erro' => 'Erro ao cadastrar categoria!'
                ));
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'mensagem' => 'Erro ao cadastrar categoria!',
                'dados' => [],
                'erro' => $e->getMessage()
            ));
        }

        break;

    case "insert_update_cat_receitas":

        $categoria = $obj->categoria;
        $data = array();

        try {

            $pdo->beginTransaction();

            if ($categoria->CodReceita > 0) {
                $mensagem = 'Categoria receita atualizada com sucesso!';
                $sql  = "UPDATE CatReceitas SET DescReceita = :DescReceita WHERE CodReceita = '$categoria->CodReceita'";
            } else {
                $mensagem = 'Categoria receita cadastrada com sucesso!';
                $sql  = 'INSERT INTO CatReceitas(DescReceita) VALUES(:DescReceita)';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':DescReceita', $categoria->DescReceita, PDO::PARAM_STR);
            $stmt->execute();

            $sql      = 'SELECT * FROM CatReceitas ORDER BY CodReceita ASC';
            $stmt     =    $pdo->prepare($sql);

            $stmt->execute();

            while ($row  = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }

            if ($pdo->commit()) {
                echo json_encode(array(
                    'mensagem' => $mensagem,
                    'dados' => $data,
                    'categoria' => 'R',
                    'erro' => ''
                ));
            } else {
                $pdo->rollBack();
                echo json_encode(array(
                    'mensagem' => 'Erro ao cadastrar categoria!',
                    'dados' => [],
                    'erro' => 'Erro ao cadastrar categoria!'
                ));
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'mensagem' => 'Erro ao cadastrar categoria!',
                'dados' => [],
                'erro' => $e->getMessage()
            ));
        }

        break;
}
