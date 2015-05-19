<%@page import="java.io.PrintWriter"%>
<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Lote"%>

<%
	if(!request.getParameter("numeroLote").equals("")){
		Lote lote = new WebUtils(request).criarDownloadLote();
		new WebUtils(request).armazenarLote(lote.efetuarDownload(request));
		response.sendRedirect("retornoDownloadLote.jsp");
	}else{
		response.sendRedirect("erro.jsp");
	}
%>