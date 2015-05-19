<%@page import="br.com.cbmp.ecommerce.pedido.StatusTransacao"%>
<%@page import="br.com.cbmp.ecommerce.resposta.Transacao"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Pedido"%>
<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>
<%@page import="java.util.Date"%>
<%@ page errorPage="novoPedidoErro.jsp" %>
<%
	Pedido pedido = new WebUtils(request).recuperarUltimoPedido();
	
	boolean pedidoFinalizado = pedido.finalizar();	
	Transacao transacao = pedido.getTransacao();
%>
<html>
	<head>
		<title>Cielo - Loja Exemplo</title>
	</head>
	<center>
		<h3>Fechamento (<%= new Date() %>)</h3>
		<table border="1">
			<tr>
				<th>Número pedido</th>
				<th>Finalizado com sucesso?</th>
				<th>Transação</th>
				<th>Status transação</th>
			</tr>
			<tr>
				<td><%= pedido.getNumero() %></td>
				<td><%= pedidoFinalizado ? "sim" : "não" %></td>
				<td><%= transacao.getTid() %></td>
				<td style="color: red;"><%= transacao.getStatusTransacao() %></td>
			</tr>			
		</table>				
		<h3>XML</h3>
		<textarea name="xmlRetorno" cols="80" rows="25">
<%= transacao.getConteudo() %>
		</textarea>
		
		<p><a href="menu.html">Menu</a></p>
		<p><a href="pedidos.jsp">Pedidos</a></p>
	</center>
</html>
