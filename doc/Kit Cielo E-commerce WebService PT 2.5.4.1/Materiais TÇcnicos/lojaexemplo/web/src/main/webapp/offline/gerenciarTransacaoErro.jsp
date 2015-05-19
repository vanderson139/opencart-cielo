<%@ page isErrorPage="true" %>
<%
	exception.printStackTrace();
%>
<html>
	<head>
		<title>Pagamento VISA</title>
	</head>
	<body>
		<p>A operação não pôde ser executada!</p>
		<p>Erro: <%= exception.getMessage() %></p>
		<p>
			<input type="button" onclick="javascript: window.close(); " value="Fechar"/>
		</p>
	</body>
</html>