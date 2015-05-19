<%@page import="br.com.cbmp.ecommerce.pedido.Pedido"%>
<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>

<%	
	Pedido pedido = new WebUtils(request).criarPedido();
%>

<html>
	<head>
		<title>Pagamento 
			<% 
			try {
				switch(Integer.parseInt(request.getParameter("codigoBandeira"))) {
				case 1:
					out.print("VISA");
					break;
				case 2:
					out.print("MASTERCARD");
					break;
				case 3:
					out.print("ELO");
					break;
				case 4:
					out.print("AMEX");
					break;
				case 5:
					out.print("DINERS");
					break;
				case 6:
					out.print("DISCOVER");
					break;
				case 7:
					out.print("AURA");
					break;
				case 8:
					out.print("JCB");
					break;
				case 9:
					out.print("Celular");
					break;
				default:
					throw new Exception("Bandeira não especificada ou inválida");
				}
			}
			catch (Exception e) {
					out.print("Cartão");
			}
			%>
		</title>		
	</head>
	<body onload="document.forms[0].submit();">
		<form name="frmpagamento" method="post" action="novoPedido.jsp">
			Redirecionando...			
		</form>
	</body>
</html>