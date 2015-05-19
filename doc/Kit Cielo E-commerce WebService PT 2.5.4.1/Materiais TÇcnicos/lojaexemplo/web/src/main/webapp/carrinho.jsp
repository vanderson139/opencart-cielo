<%@page import="br.com.cbmp.ecommerce.pedido.Produto"%>
<%@page import="br.com.cbmp.ecommerce.util.Produtos"%>
<%@page import="br.com.cbmp.ecommerce.pedido.IndicadorAutorizacao"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Modalidade"%>
<%@page import="br.com.cbmp.ecommerce.util.web.WebUtils"%>
<html>
	<head>
		<title>Loja Exemplo : Pedidos</title>
	</head>
	<center>
		<h2>
			Carrinho
		</h2>
		<form action="novoPedidoAguarde.jsp" method="post" >
		<input type="hidden" name="formatoMensagem" value="requisicaoTransacao">
			<table border="1">
				<tr>
					<td>Produto</td>
					<td>
						<select name="produto">
						<% for (Produto produto : Produtos.todos()) {%>
							<option value="<%= produto.getId() %>"><%= produto.getDescricao() %></option>
						<% } %>	
						</select>						 
						<input type="text" style="width: 98px; height: 21px" name="prodValor" value="1000"/>
					</td>
				</tr>
				<tr>
					<td>Taxa de Embarque</td>
					<td>
						<input type="text" style="width: 98px; height: 21px" name="pedido.taxaEmbarque" value="1500"/>
						<input type="checkbox" name="comTaxaEmbarque" value="comTaxaEmbarque" />Com Taxa de Embarque						 
					</td>			
				</tr>
				<tr>
					<td>Forma de pagamento</td>
					<td>
						<select name="codigoBandeira">
							<option value="1">Visa</option>
							<option value="2">Mastercard</option>
							<option value="3">Elo</option>
							<option value="4">Amex</option>
							<option value="5">Diners</option>
							<option value="6">Discover</option>
							<option value="7">Aura</option>
							<option value="8">JCB</option>
						</select>
						<br/>					
						<input type="radio" name="formaPagamento" value="D">Débito
						<br><input type="radio" name="formaPagamento" value="C" checked>Crédito à Vista
						<br><input type="radio" name="formaPagamento" value="2">2x
						<br><input type="radio" name="formaPagamento" value="3">3x
						<br><input type="radio" name="formaPagamento" value="4">4x
						<br><input type="radio" name="formaPagamento" value="5">5x
						<br><input type="radio" name="formaPagamento" value="6">6x
						<br><input type="radio" name="formaPagamento" value="7">7x
						<br><input type="radio" name="formaPagamento" value="8">8x
						<br><input type="radio" name="formaPagamento" value="9">9x
						<br><input type="radio" name="formaPagamento" value="10">10x
						<br><input type="radio" name="formaPagamento" value="11">11x
						<br><input type="radio" name="formaPagamento" value="12">12x						
						<br><input type="radio" name="formaPagamento" value="18">18x
						<br><input type="radio" name="formaPagamento" value="36">36x
						<br><input type="radio" name="formaPagamento" value="56">56x<br/>
					</td>
				</tr>
				<tr>
					<td>Token</td>
					<td><input type="text" style="width: 350px;" name="cartao.token"/></td>
				</tr>
				<tr>
					<td>Soft Descriptor</td>
					<td>
						<input type="text" style="width: 250px;" name="pedido.softDescriptor"/>
					</td>
				</tr>
				<tr>
					<td>Configuração</td>
					<td>
						<table>
							<tr>
								<td>
									Parcelamento
								</td>
								<td>
									<select name="tipoParcelamento">
										<option value="<%= Modalidade.PARCELADO_LOJA.getCodigo() %>">Loja</option>
										<option value="<%= Modalidade.PARCELADO_ADMINISTRADORA.getCodigo() %>">Administradora</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Capturar Automaticamente?</td>
								<td>
									<select name="capturarAutomaticamente">
										<option value="true">Sim</option>
										<option value="false" selected="selected">Não</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Autorização Automática</td>
								<td>
									<select name="indicadorAutorizacao">
										<% for (IndicadorAutorizacao ind : IndicadorAutorizacao.values()) { %>
										<option value="<%= ind.getCodigo() %>"><%= ind.getDescricao() %></option>
										<% } %>
									</select>
								</td>
							</tr>
							<tr>
								<td>URL Retorno</td>
								<td>
									<input type="text" style="width: 319px;" name="urlRetorno" value="<%= new WebUtils(request).getUrlRetorno() %>"/>
								</td>
							</tr>
							<tr>
								<td>
									Idioma
								</td>
								<td>
									<select name="idioma">
										<option value="PT">pt-br</option>
										<option value="EN">en-us</option>
										<option value="ES">es-es</option>						
									</select>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<input type="checkbox" name="gerarToken" value="gerarToken" />Gerar Token
									<input type="hidden" name="pedido.loja" value="1001734898">
								</td>
							</tr>
						</table>
					</td>
				</tr>		
				<tr>
					<td align="center" colspan="2">
						<input type="submit" value="Pagar"/>
					</td>
				</tr>
			</table>
		</form>
		<a href="menu.html">Menu</a>
	</center>
</html>