<?php

	require 'Slim/Slim.php';

	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();

	// CONEXÃO COM O BD
	function getConn() {

		return new PDO('mysql:host=localhost;dbname=to_do_list', 'root', 'hasdran130',
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}

	$app->delete('/tasks/:id', function ($id) {
	    $sql = "DELETE FROM tasks WHERE id = :id";
		
		$conn = getConn();		
		$stmt = $conn->prepare($sql);
		$stmt->bindParam("id", $id);

		if($stmt->execute()) {
			echo json_encode(array(['msg' => "[OK] Removida com Sucesso!"]));	 	
		}	
	});

	$app->post('/addtask', function() use ($app) {
		$dadoJson = json_decode($app->request()->getBody());

		$sql = "INSERT INTO tasks (title, description, status) VALUES (:title, :description, :status)";

		$conn = getConn();		
		$stmt = $conn->prepare($sql);
		$stmt->bindParam("title", $dadoJson->title);
		$stmt->bindParam("description", $dadoJson->description);
		$stmt->bindParam("status", $dadoJson->status);

		if($stmt->execute()) {
			echo json_encode(array(['msg' => "[OK] Entrada Registrada com Sucesso!"]));	 	
		}		
	});

	$app->get('/tasks', function() use ($app) {
		$sql = "SELECT * FROM tasks WHERE 1";
		
		$conn = getConn();		
		$stmtEntrada = $conn->prepare($sql);

		if($stmtEntrada->execute()) {
			$result = $stmtEntrada->fetchAll(PDO::FETCH_ASSOC);
		}
		echo json_encode($result);	
	});

	$app->run();
?>
