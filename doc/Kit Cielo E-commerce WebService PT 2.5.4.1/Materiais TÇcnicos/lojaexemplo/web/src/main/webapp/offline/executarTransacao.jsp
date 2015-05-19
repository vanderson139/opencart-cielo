<%@page import="br.com.cbmp.ecommerce.util.web.GerenciadorTransacao"%>
<%@ page errorPage="gerenciarTransacaoErro.jsp" %>
<%
	String resultado = new GerenciadorTransacao(request).executar();
%>
<html>
	<body>
		<textarea name="xmlRetorno" cols="73" rows="40"><%= resultado %></textarea>
		<center>
			<p>
				<input type="button" onclick="javascript: window.close();" value="Fechar"/>
			</p>
		<center>
	</body>
</html>