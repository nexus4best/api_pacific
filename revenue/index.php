<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
//header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json;charset=utf-8');


	$host = '10.19.19.50'; 
	$user = 'sa'; 
	$pass = 'w,jgvkojk'; 
	$dbname = 'Office'; 

	$CshDatabaseServerAlone = $_GET['CshDatabaseServerAlone'];
	$CshCode = $_GET['CshCode'];
	$BrnName = $_GET['BrnName'];
	$CshReceiptPosCashier = $_GET['CshReceiptPosCashier'];

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
	//echo "Connected to the $host/$dbname server OK:<br>\n";

	// Simple SELECT query with no error reporting
/*
			WHERE 
				T_Cashier.CshDatabaseServerAlone LIKE '%$CshDatabaseServerAlone%' AND
				T_Cashier.CshCode = '$CshCode' AND
				T_Branch.BrnName LIKE '%$BrnName%'
*/
	if(isset($CshReceiptPosCashier)){
		$sql="
			SELECT 
				T_Branch.BrnName,
				T_Cashier.CshCode, T_Cashier.CshName, T_Cashier.CshDatabaseServerAlone, T_Cashier.CshReceiptPosCashier
			FROM 
				T_Branch 
			JOIN 
				T_Cashier
			ON 
				T_Branch.BrnCode=T_Cashier.CshBranch		
			WHERE
				T_Branch.BrnName <> 'สำนักงานใหญ่' AND
				T_Cashier.CshDatabaseServerAlone NOT LIKE 'S066%' AND
				T_Cashier.CshReceiptPosCashier LIKE '%$CshReceiptPosCashier%'
			ORDER BY 
				T_Cashier.CshDatabaseServerAlone ASC

	";
	} elseif(isset($BrnName)){
		$sql="
			SELECT 
				T_Branch.BrnName,
				T_Cashier.CshCode, T_Cashier.CshName, T_Cashier.CshDatabaseServerAlone, T_Cashier.CshReceiptPosCashier
			FROM 
				T_Branch 
			JOIN 
				T_Cashier
			ON 
				T_Branch.BrnCode=T_Cashier.CshBranch		
			WHERE
				T_Cashier.CshDatabaseServerAlone NOT LIKE 'S066%' AND
				T_Branch.BrnName LIKE '%$BrnName%' AND
				T_Branch.BrnName <> 'สำนักงานใหญ่' 
			ORDER BY 
				T_Cashier.CshDatabaseServerAlone ASC

	";
	} elseif(isset($CshDatabaseServerAlone)){
		$sql="
			SELECT 
				T_Branch.BrnName,
				T_Cashier.CshCode, T_Cashier.CshName, T_Cashier.CshDatabaseServerAlone, T_Cashier.CshReceiptPosCashier
			FROM 
				T_Branch 
			JOIN 
				T_Cashier
			ON 
				T_Branch.BrnCode=T_Cashier.CshBranch		
			WHERE
				T_Cashier.CshDatabaseServerAlone LIKE '%$CshDatabaseServerAlone%' AND
				T_Cashier.CshDatabaseServerAlone NOT LIKE 'S066%' AND
				T_Branch.BrnName <> 'สำนักงานใหญ่' 
			ORDER BY 
				T_Cashier.CshDatabaseServerAlone ASC

	";
	} elseif(isset($CshCode)){
		$sql="
			SELECT 
				T_Branch.BrnName,
				T_Cashier.CshCode, T_Cashier.CshName, T_Cashier.CshDatabaseServerAlone, T_Cashier.CshReceiptPosCashier
			FROM 
				T_Branch 
			JOIN 
				T_Cashier
			ON 
				T_Branch.BrnCode=T_Cashier.CshBranch		
			WHERE
				T_Cashier.CshCode = '$CshCode' AND
				T_Cashier.CshDatabaseServerAlone NOT LIKE 'S066%' AND
				T_Branch.BrnName <> 'สำนักงานใหญ่' 
			ORDER BY 
				T_Cashier.CshDatabaseServerAlone ASC

	";
	}
			


	$STH = $DBH->query($sql);
	$STH->setFetchMode(PDO::FETCH_ASSOC);
	$row=$STH->fetchall();

	$sqlg="
			SELECT T_Cashier.CshCode FROM T_Cashier
			WHERE T_Cashier.CshCode <> 'R10'
			GROUP BY T_Cashier.CshCode
			ORDER BY T_Cashier.CshCode ASC
	";
	$STHg = $DBH->query($sqlg);
	$STHg->setFetchMode(PDO::FETCH_ASSOC);
	$rowg=$STHg->fetchall();	

	$res = [
		'data' => $row,
		'cshCodeGroup' => $rowg
	];

	echo json_encode($res);

?>
