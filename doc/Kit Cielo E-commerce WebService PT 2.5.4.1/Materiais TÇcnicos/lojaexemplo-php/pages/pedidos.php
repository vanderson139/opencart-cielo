<?php 

	require_once "../includes/include.php";
	
	// constantes
	$vmDadosPedido = "dados-pedido";
	$vmDataHora = "data-hora";
	$vmFormaPagamento = "forma-pagamento";
	$td = "				";
	$tr = "			";
	
	if(isset($_GET["clear"]))
	{
		if($_GET["clear"])
			$_SESSION["pedidos"] = new ArrayObject();
	}
?>

<html>
	<head>
		<title>Loja Exemplo : Pedidos</title>
		<script type="text/javascript">
			function executar() {
				window.open("", "tela_operacao", "toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=1,resizable=0,screenX=0,screenY=0,left=0,top=0,width=625,height=725");				
				return true;
			}		
		</script>
	</head>
	<center>
		<h2>
			Pedidos Efetuados
		</h2>
		<p>
			(últimos 20)
		</p>
		
		<table border="1">
			<tr>				
				<th>Pedido</th>
				<th>Data</th>
				<th>Valor</th>
				<th>Modalidade</th>
				<th>Parcelas</th>
				<th>TID</th>
				<th>Status Transação</th>
				<th>Valor a capturar</th>
				<th>Ação</th>
				<th></th>
			</tr>
			
<?php 
	if($_SESSION["pedidos"]->count() > 0)
	{
		$iterator = $_SESSION["pedidos"]->getIterator();
		
		while($iterator->valid())
		{
			$objPedido = new Pedido();						
			$objPedido->FromString($iterator->current());
			
			switch($objPedido->formaPagamentoProduto)
			{
				case "A":
					$modalidade = "Débito";
					break;
				case "1":
					$modalidade = "Crédito";
					break;
				case "2":
					$modalidade = "Parcelado Loja";
					break;
				case "3":
					$modalidade = "Parcelado Admin";
					break;
				default:
					$modalidade = "n/a";
					break;
			}
			
			echo $tr . '<form action="operacao.php" target="tela_operacao" onsubmit="javascript:executar();" method="post">' . "\n";
			echo $tr . '<input type="hidden" name="numeroPedido" value="' . $objPedido->dadosPedidoNumero . '"/>' . "\n";
			echo $tr . '<input type="hidden" name="key" value="' . $iterator->key() . '"/>' . "\n";
			echo $tr . "<tr>\n";
			echo $td . "<td>" . $objPedido->dadosPedidoNumero . "</td>\n";
			echo $td . "<td>" . $objPedido->dadosPedidoData . "</td>\n";
			echo $td . '<td align="right">' . $objPedido->dadosPedidoValor . "</td>\n";
			echo $td . "<td>" . $modalidade . "</td>\n";
			echo $td . "<td>" . $objPedido->formaPagamentoParcelas . "</td>\n";
			echo $td . "<td>" . $objPedido->tid . "</td>\n";
			echo $td . '<td style="color: red;">' . $objPedido->getStatus() . "</td>\n";
			echo $td . '<td align="right">'. "\n" . $td .
				'	<select name="percentualCaptura">' . "\n" . $td .
				'		<option value="100">100%</option>' . "\n" . $td .
				'		<option value="90">90%</option>' . "\n" . $td .
				'		<option value="30">30%</option>' . "\n" . $td .
				'	</select>' . "\n" . $td .
				"</td>\n";
			echo $td . '<td align="right">'. "\n" . $td .
				'	<select name="acao">' . "\n" . $td .
				'		<option value="autorizar">Autorizar</option>' . "\n" . $td .
				'		<option value="capturar">Capturar</option>' . "\n" . $td .
				'		<option value="cancelar">Cancelar</option>' . "\n" . $td .
				'		<option value="consultar">Consultar</option>' . "\n" . $td .
				'	</select>' . "\n" . $td .
				"</td>\n";
			echo $td . '<td><input type="submit" value="Executar" /></td>' . "\n"; 
			echo $tr . "</tr>\n";
			echo $tr . "</form>\n\n";
			
			$iterator->next();
		}
		
	}
	else 
	{
		echo $tr . "<tr>\n";
		echo $td . '<td colspan="10" style="text-align: center;">Nenhum pedido encontrado</td>';
		echo $tr . "</tr>\n";
	}
			
?>
		</table>		
		<p>
			<a href="index.php">Menu</a>
		</p>
		<p>
			<a href="#" onclick="window.location.href = window.location.href + '?clear=true'">Limpar Session</a>
		</p>
	</center>
</html>