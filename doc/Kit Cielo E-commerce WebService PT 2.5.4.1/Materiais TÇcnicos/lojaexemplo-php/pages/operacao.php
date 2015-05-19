<?php 

	require "../includes/include.php";

	$vmDadosEc = "dados-ec";
	
	$key = $_POST["key"];
	$acao = $_POST["acao"];
	
	$objResposta = null;
	
	$Pedido = new Pedido();
	$Pedido->FromString($_SESSION["pedidos"]->offsetGet($key));
	
	switch($acao)
	{
		case "autorizar":  
			$objResposta = $Pedido->RequisicaoAutorizacaoTid();
			break;
		case "capturar": 
			switch($_POST["percentualCaptura"])
			{
				case '90':
					$PercentualCaptura = $Pedido->dadosPedidoValor * 0.9;
					break;
				case '30':
					$PercentualCaptura = $Pedido->dadosPedidoValor * 0.3;
					break;
				default:
					$PercentualCaptura = $Pedido->dadosPedidoValor;
					break;
			}
			$objResposta = $Pedido->RequisicaoCaptura($PercentualCaptura, null);
			break;
		case "cancelar":
			$objResposta = $Pedido->RequisicaoCancelamento();
			break;
		case "consultar": 
			$objResposta = $Pedido->RequisicaoConsulta();
			break; 
	}
	
	// Atualiza status
	$Pedido->status = $objResposta->status;
	
	// Atualiza Pedido da SESSION
	$StrPedido = $Pedido->ToString();
	$_SESSION["pedidos"]->offsetSet($key, $StrPedido);
	
?>



<html>
	<body>
		<textarea name="xmlRetorno" cols="73" rows="40" readonly="readonly">
<?php
	
	echo htmlentities($objResposta->asXML());
	
?>
		</textarea>
		<center>
			<p>
				<input type="button" onclick="javascript:window.opener.location.reload(); window.close();" value="Fechar"/>
			</p>
		</center>
	</body>
</html>