<?php
if(!isset($_POST['cod_moip'])){
	header("Location: index.php?route=checkout/success");
}

if ( isset($_POST['id_transacao']) AND isset($_POST['status_pagamento'])) {
	$id_transacao		=	$_POST['id_transacao'];
	$status_pagamento	=	$_POST['status_pagamento'];
	$valor				= 	$_POST['valor'];
	$tipopagamento      =   $_POST['tipo_pagamento'];
	$emailcomprador     =   $_POST['email_consumidor'];
	$codigomoip         =   $_POST['cod_moip'];
	
	$moipposts = "retornoalto=sim";
		  
	foreach ($_POST as $key => $value)
	{
	 $value = urlencode(stripslashes($value));
	 $moipposts .= "&$key=$value";
	}

	require_once("config.php");
	$db = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
	mysql_select_db(DB_DATABASE, $db);

	$SQL = "SELECT TransacaoID FROM moiptransacoes WHERE TransacaoID ='".$id_transacao."'";
	$Executa = mysql_query($SQL,$db) or print(mysql_error());
	$totalresultado = mysql_num_rows($Executa);
	if($totalresultado!=0){
		mysql_free_result($Executa); 
		$SQL = "UPDATE moiptransacoes SET  StatusPagamento='".$status_pagamento."' WHERE TransacaoID='".$id_transacao."'";	
		$Executa = mysql_query($SQL,$db) or print(mysql_error()); 
	} else {
		$SQL = "INSERT INTO moiptransacoes (TransacaoID,Valor,StatusPagamento,cod_moip,tipo_pagamento,email_consumidor) VALUES (
			'" .$id_transacao . "', " .
			"'" . $valor . "', " .
			"'" . $status_pagamento . "', " .
			"'" . $codigomoip . "', " .
			"'" . $tipopagamento . "', " . 
			"'" . $emailcomprador . "'" .
			")";
		 
		$Executa = mysql_query($SQL,$db) or print(mysql_error()); 
	}
	mysql_close($db);

	/* Envia o callback */
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, HTTP_SERVER.'index.php?route=payment/moip/callback');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $moipposts);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$resp = curl_exec($ch);
	curl_close($ch);

	if (isset($errors) ){
			header("HTTP 200");
		} else {
			header("HTTP 500");
		}

	exit;
}
?>