<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>
<%@page import="br.com.cbmp.ecommerce.requisicao.RequisicaoLote" %>

<%
	String resposta = new RequisicaoLote().enviarPara(request);;

	if(resposta.equals("")) {
		response.sendRedirect("erroUploadLote.jsp");
	}
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>XML</title>
	</head>
	<body>
		<center>
			<h2>Resposta:</h2>
			<textarea cols="80" rows="25"><%=resposta%></textarea><br /><br />
			<a href="uploadLote.jsp">Upload</a><br />
			<a href="../menu.html">Menu</a>
		</center>
	</body>
</html>