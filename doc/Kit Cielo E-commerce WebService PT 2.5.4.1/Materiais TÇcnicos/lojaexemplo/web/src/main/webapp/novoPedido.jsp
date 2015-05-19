<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Pedido"%>
<%@page import="br.com.cbmp.ecommerce.resposta.Transacao"%>
<%@ page errorPage="novoPedidoErro.jsp" %>

<%
	// solicita criação da transação
	Pedido pedido = new WebUtils(request).recuperarUltimoPedido();		
	Transacao transacao = pedido.criarTransacao();
	
	// obtém URL de redirecionamento
	String urlRedirecionamento;
	urlRedirecionamento = transacao.getUrlAutenticacao();
	
	
	if(urlRedirecionamento == null){
		urlRedirecionamento = new WebUtils(request).getUrlRetorno();
	}

	// redireciona o fluxo para a Cielo
	System.out.print("urlRedirecionamento: " + urlRedirecionamento);
	
	response.sendRedirect(urlRedirecionamento);
%>