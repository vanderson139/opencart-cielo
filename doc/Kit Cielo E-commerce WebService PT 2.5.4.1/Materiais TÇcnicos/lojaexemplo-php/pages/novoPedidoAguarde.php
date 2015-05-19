<?php 

	require "../includes/include.php";
	
?>
<html>
	<head>
		<title>Pagamento <?php echo strtoupper($_POST["codigoBandeira"]); ?></title>		
	</head>
	<body>
		Redirecionando...		
<?php

	$Pedido = new Pedido();
	
	// Lê dados do $_POST
	$Pedido->formaPagamentoBandeira = $_POST["codigoBandeira"]; 
	if($_POST["formaPagamento"] != "A" && $_POST["formaPagamento"] != "1")
	{
		$Pedido->formaPagamentoProduto = $_POST["tipoParcelamento"];
		$Pedido->formaPagamentoParcelas = $_POST["formaPagamento"];
	} 
	else 
	{
		$Pedido->formaPagamentoProduto = $_POST["formaPagamento"];
		$Pedido->formaPagamentoParcelas = 1;
	}
	
	$Pedido->dadosEcNumero = CIELO;
	$Pedido->dadosEcChave = CIELO_CHAVE;
	
	$Pedido->capturar = $_POST["capturarAutomaticamente"];	
	$Pedido->autorizar = $_POST["indicadorAutorizacao"];
	
	$Pedido->dadosPedidoNumero = rand(1000000, 9999999); 
	$Pedido->dadosPedidoValor = $_POST["produto"];
	
	$Pedido->urlRetorno = ReturnURL();
	
	// ENVIA REQUISIÇÃO SITE CIELO
	$objResposta = $Pedido->RequisicaoTransacao(false);
	
	$Pedido->tid = $objResposta->tid;
	$Pedido->pan = $objResposta->pan;
	$Pedido->status = $objResposta->status;
	
	$urlAutenticacao = "url-autenticacao";
	$Pedido->urlAutenticacao = $objResposta->$urlAutenticacao;

	// Serializa Pedido e guarda na SESSION
	$StrPedido = $Pedido->ToString();
	$_SESSION["pedidos"]->append($StrPedido);
	

	echo '<script type="text/javascript">
			window.location.href = "' . $Pedido->urlAutenticacao . '"
		 </script>';

?>

	</body>
</html>