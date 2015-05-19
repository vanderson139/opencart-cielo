<%@page import="java.util.Date"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Pedido"%>
<%@page import="br.com.cbmp.ecommerce.resposta.Transacao"%>
<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>
<%@ page errorPage="novoPedidoErro.jsp" %>

<%
	WebUtils webUtils = new WebUtils(request);
	Pedido pedido = webUtils.criarPedido();
	
	if(webUtils.isRequisicaoTransacao()){
		Transacao transacao = pedido.criarTransacao();
		//caso o fluxo da transacao precise que seja feito o redirecionamento,
		//não deve vir a URL de autenticação. (Ainda falta confirmar isso)
		if (transacao.getUrlAutenticacao() != null){
			response.sendRedirect(transacao.getUrlAutenticacao());			
		}
	} else {
		pedido.finalizarComAutorizacaoDireta();
		//getServletConfig().getServletContext().getRequestDispatcher("/retorno.jsp").forward(request, response);
	}
	getServletConfig().getServletContext().getRequestDispatcher("/retorno.jsp").forward(request, response);
%>