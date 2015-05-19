<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>upload Lote</title>
	</head>
	<body>
		<center>
			<h2>Upload Lote</h2>
			<form name="frmUpload" method="post" action="retornoLote.jsp" enctype="multipart/form-data">
				<table border="1">
					<tr>
						<td align="left">Arquivo:</td>
						<td align="left"><input type="file" name="arqLoteUp" id="arqLoteUp"/></td>
					</tr>	
					<tr>
						<td align="center" colspan="2"><input type="submit" value="Enviar"/></td>
					</tr>	
				</table>
			</form>
			<a href="../menu.html">Menu</a>
		</center>
	</body>
</html>