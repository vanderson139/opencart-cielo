<%@page import="br.com.cbmp.ecommerce.resposta.Transacao.DadosToken"%>
<%@page import="br.com.cbmp.ecommerce.pedido.StatusTransacao"%>
<%@page import="br.com.cbmp.ecommerce.resposta.Transacao"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Pedido"%>
<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>
<%@page import="java.util.Date"%>
<%
	Pedido pedido = new WebUtils(request).recuperarUltimoPedido();

	Transacao transacao = pedido.getTransacao();
	
	DadosToken dadosToken = transacao.getToken().getDadosToken();	
	
%>
<html>
	<head>
		<title>Cielo - Loja Exemplo</title>
	</head>
	<center>
		<h3>Fechamento (<%= new Date() %>)</h3>
		<table border="1">
			<tr>
				<th>Cód Token</th>
				<th>N° Cartão</th>
				<th>Status</th>				
			</tr>
			<tr>
				<td><%= dadosToken.getCodigoToken() %></td>
				<td><%= dadosToken.getNumeroCartaoTruncado() %></td>
				<td><%= dadosToken.getStatus() %></td>
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
