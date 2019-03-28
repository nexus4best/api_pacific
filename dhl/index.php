<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
//header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json;charset=utf-8');


	$host = '10.19.19.50'; 
	$user = 'sa'; 
	$pass = 'w,jgvkojk'; 
	$dbname = 'Office'; 

	$BrnCode = $_GET['BrnCode'];

	try // Connect to server with try/catch error reporting
	  {
	  	$DBH = new PDO('dblib:host='.$host.';dbname='.$dbname, $user, $pass);
	  	$DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	  }
	catch(PDOException $e)
	  {
	  	echo "Couldn't connect to $host/$dbname: ".$e->getMessage();
	  	exit();
      }
      
      $sql="
        SELECT 
            BrnCode, BrnName, BrnAddress, BrnTel
        FROM 
            T_Branch 	
        WHERE
            BrnCode = '$BrnCode'
    ";

	$STH = $DBH->query($sql);
	$STH->setFetchMode(PDO::FETCH_ASSOC);
	$row=$STH->fetchall();

	echo json_encode($row);

?>
