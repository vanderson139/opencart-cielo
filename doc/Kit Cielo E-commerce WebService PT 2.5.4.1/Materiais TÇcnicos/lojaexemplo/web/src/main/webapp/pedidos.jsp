<%@page import="java.util.Collection"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Pedido"%>
<%@page import="br.com.cbmp.ecommerce.util.Pedidos"%>
<%@page import="br.com.cbmp.ecommerce.resposta.Transacao"%>
<%@page import="br.com.cbmp.ecommerce.pedido.StatusTransacao"%>
<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>
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
				<th>Valor a Cancelar</th>
				<th>Valor da Operação</th>
				<th>Ação</th>
				<th></th>
			</tr>
			<%
			
			
			Pedidos listaPedidos = new WebUtils(request).getPedidos();
			
			Collection<Pedido> pedidos = listaPedidos.todos();
			
			for (Pedido pedido : pedidos) {
				Transacao transacao = pedido.getTransacao();
			%>
			
				<form action="operacao.jsp" target="tela_operacao" onsubmit="javascript:executar();" method="post">
				<input type="hidden" name="numeroPedido" value="<%= pedido.getNumero() %>"/>
				<tr>				
					<td><%= pedido.getNumero() %></td>
					<td><%= pedido.getDataFormatada() %></td>
					<td align="right"><%= pedido.getValor() %></td>
					<td><%= pedido.getFormaPagamento().getModalidade() %></td>
					<td><%= pedido.getFormaPagamento().getParcelas() %></td>
					<td><%= transacao != null ? transacao.getTid() : "n/a"%></td>
					<td style="color: red;"><%= transacao != null ? transacao.getStatusTransacao() : "n/a"%></td>
					<td align="right">
						<select name="percentualCaptura">
							<option value="100">100%</option>
							<option value="90">90%</option>
							<option value="30">30%</option>
						</select>						
					</td>
					<td align="right">
						<input type="text" name="valorOperacao" style="width: 80px" value="0"></input>						
					</td>
					<td>
						<select name="acao">
							<option value="autorizar">Autorizar</option>
							<option value="capturar">Capturar</option>
							<option value="cancelar">Cancelar</option>
							<option value="consultar">Consultar</option>
						</select>
					</td>
					<td><input type="submit" value="Executar" /></td>
				</tr>
				</form>			
			
			<% } %>
			
		</table>		
		<p>
			<a href="menu.html">Menu</a>
		</p>
	</center>
</html>