<?php require "../includes/include.php"; ?>
<html>
	<head>
		<title>Loja Exemplo : Gerenciar Transação</title>
		<script type="text/javascript">
			function executar() {
				var tid = document.getElementById('tid').value;
				if (tid!=null && tid!=""){				
					window.open("", "telaGerenciamento", "toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=1,resizable=0,screenX=0,screenY=0,left=0,top=0,width=625,height=725");				
					return true;
				}else{
					alert('Atenção é necessário entrar com o TID.');
					return false;
				}
			}		
		</script>
	</head>
	<center>
		<h2>
			Gerenciar Transação
		</h2>
		<form action="executarTransacao.php" target="telaGerenciamento" onsubmit="return executar();" method="post">
		<table border="1">
			<tr>
				<td>Loja</td>
				<td>
					<select name="numeroLoja">
						<option value="1006993069">1006993069 (cartão na Loja)</option>
						<option value="1001734898">1001734898 (cartão na Cielo)</option>
						<option value="1010111100">1010111100 (loja inexistente)</option>
					</select>
				</td>				
			</tr>
			<tr>
				<td>TID</td>
				<td>
					<input type="text" name="tid" size="30" id="tid"/>
				</td>				
			</tr>	
			<tr>
				<td>Ação</td>
				<td>
					<select name="acao">
						<option value="AUTORIZACAO">Autorizar</option>
						<option value="CAPTURA">Capturar</option>
						<option value="CANCELAMENTO">Cancelar</option>
						<option value="CONSULTA">Consultar</option>
					</select>				
				</td>				
			</tr>	
			<tr>
				<td>Valor Captura (R$ 1,00 = 100)</td>
				<td>
					<input type="text" name="valor" size="10"/>
				</td>				
			</tr>	
			<tr align="center">
				<td colspan="2">
					<input type="submit" value="Executar"/>
				</td>
			</tr>																
		</table>
		</form>		
		<p>
			<a href="index.php">Menu</a>
		</p>
	</center>
</html>