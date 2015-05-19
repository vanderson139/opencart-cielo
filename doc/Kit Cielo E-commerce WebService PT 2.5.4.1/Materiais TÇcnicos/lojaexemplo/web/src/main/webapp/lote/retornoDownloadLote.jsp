<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Lote"%>
<%@page errorPage="novoPedidoErro.jsp"%>
<%@page import="java.util.Date"%>
<%
	Lote lote = new WebUtils(request).recuperarUltimoLote();
%>
<html>
<head>
</head>
	<title>Cielo - Loja Exemplo</title>
<body>	
	<center>
		<h3>Download Retorno Lote (<%= new Date() %>)</h3>
		<table border="1">
			<tr>
				<th>Numero do Lote</th>
				<th>Download efetuado?</th>
				<th>Endereço do arquivo</th>
				<th>Nome do Arquivo</th>
			</tr>
			<tr>
				<td><%= lote.getNumeroLote() %></td>
				<td><%= lote.getPath() != null ? "sim" : "não" %></td>
				<td><a href="<%= lote.getPath() != null ? lote.getPath() : "#" %>" >Abrir Arquivo</a></td>
				<td><%= lote.getName() != null ? lote.getName() : "" %></td>
			</tr>		
		</table>	
		<h3><%= lote.getPath() != null ? "Conteudo do Arquivo" : "Mensagem de Retorno"%></h3>
		
		<textarea name="xmlRetorno" cols="80" rows="25"><%= lote.getXmlRetorno() != null ? lote.getXmlRetorno().trim() : "" %></textarea>
		<p>
			<a href="../menu.html">Menu</a>
		</p>
	</center>
</body>
</html>