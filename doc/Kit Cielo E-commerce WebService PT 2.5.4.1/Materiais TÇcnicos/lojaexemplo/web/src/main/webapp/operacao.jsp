<%@page import="br.com.cbmp.ecommerce.resposta.RequisicaoInvalidaException"%>
<%@page import="br.com.cbmp.ecommerce.resposta.Transacao"%>
<%@page import="br.com.cbmp.ecommerce.util.Pedidos"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Pedido"%>
<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>
<%
	String numeroPedido = request.getParameter("numeroPedido");
	String acao = request.getParameter("acao");
	
	Pedidos pedidos = new WebUtils(request).getPedidos();
	
	Pedido pedido = pedidos.recuperar(numeroPedido);
	
	if (pedido.getTransacao() == null) {
		throw new IllegalStateException("Pedido sem transação");
	}

	String respostaXml;
	
	try {		
		if ("autorizar".equals(acao)) {
			pedido.autorizarTransacao();	
		}
		else if ("capturar".equals(acao)) {
			String percentualCaptura = request.getParameter("percentualCaptura");
			Double valorACapturar = (Integer.parseInt(percentualCaptura) / 100.0) * Integer.parseInt(pedido.getValor());
			int taxaEmbarque = Integer.parseInt(request.getParameter("valorOperacao"));
			pedido.capturarTransacao(valorACapturar.longValue(), taxaEmbarque);
		}
		else if ("cancelar".equals(acao)) {
			String valorCancelamento = request.getParameter("valorOperacao");
			long lValorCancelamento = valorCancelamento.trim().equals("") ? 
					0 : Long.parseLong(valorCancelamento);
			pedido.cancelarTransacao(lValorCancelamento);
		}
		else {
			pedido.consultarTransacao();
		}
		
		respostaXml = pedido.getTransacao().getConteudo();
	}
	catch (RequisicaoInvalidaException e) {
		respostaXml = e.getErro().getConteudo();		
	}
%>
<html>
	<body>
		<textarea name="xmlRetorno" cols="73" rows="40"><%= respostaXml %></textarea>
		<center>
			<p>
				<input type="button" onclick="javascript:window.opener.location.reload(); window.close();" value="Fechar"/>
			</p>
		<center>
	</body>
</html>