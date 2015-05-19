<?php 

	require "../includes/include.php";

	$objResposta = null;
	
	$acao = $_POST["acao"];
		
	$Pedido = new Pedido();
	$Pedido->tid = $_POST["tid"]; 
	$Pedido->dadosEcNumero = $_POST["numeroLoja"];

	if($Pedido->dadosEcNumero == LOJA){
		$Pedido->dadosEcChave = LOJA_CHAVE;
	}else if($Pedido->dadosEcNumero == CIELO){
		$Pedido->dadosEcChave = CIELO_CHAVE;
	}else{
		$Pedido->dadosEcChave = md5($Pedido->dadosEcNumero);
	}
	
	switch($acao)
	{
		case "AUTORIZACAO":  
			$objResposta = $Pedido->RequisicaoAutorizacaoTid();
			break;
		case "CAPTURA": 
			$valor = $_POST["valor"];
			$objResposta = $Pedido->RequisicaoCaptura($valor, null);
			break;
		case "CANCELAMENTO":
			$objResposta = $Pedido->RequisicaoCancelamento();
			break;
		case "CONSULTA": 
			$objResposta = $Pedido->RequisicaoConsulta();
			break; 
	}

?>
<html>
	<body>
		<textarea name="xmlRetorno" cols="70" rows="40"><?php echo htmlentities($objResposta->asXML()); ?></textarea>
		<center>
			<p>
				<input type="button" onclick="javascript: window.close();" value="Fechar"/>
			</p>
		</center>
	</body>
</html>