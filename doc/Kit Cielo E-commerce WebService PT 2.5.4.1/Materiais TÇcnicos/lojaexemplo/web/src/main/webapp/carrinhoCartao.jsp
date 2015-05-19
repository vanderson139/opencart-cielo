<%@page import="br.com.cbmp.ecommerce.pedido.Produto"%>
<%@page import="br.com.cbmp.ecommerce.util.Produtos"%>
<%@page import="br.com.cbmp.ecommerce.pedido.IndicadorAutorizacao"%>
<%@page import="br.com.cbmp.ecommerce.pedido.Modalidade"%>
<html>
	<head>
		<title>Loja Exemplo : Cartão na Loja</title>
		<script type="text/javascript">
			window.onload = function() {
				var form = document.forms[0];
				form.indicadorAutorizacao[1].selected = true;
				
				window.document.frm.onsubmit = function() {					
					var tentarAutenticar = form.elements['tentarAutenticar'][0].checked;
				
					if (tentarAutenticar) {
						form.action = 'novoPedidoAguarde.jsp';
					}
				
					return true;
				}
			}
		</script>		
	</head>
	<center>
		<h2>
			Carrinho
		</h2>
		<form name="frm" action="carrinhoCartaoPagamento.jsp" method="post">
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
					<td>Tentar Autenticar?</td>
					<td>
						<input type="radio" name="tentarAutenticar" value="sim">Sim</input>
						<input type="radio" name="tentarAutenticar" value="nao" checked="checked">Não</input>
					</td>
				</tr>		
				<tr>
					<td>Cartão</td>
					<td>
						<table border="0">
							<tr>
								<td>Número</td>
								<td><input type="text" name="cartao.numero" value="4551870000000183"></td>
							</tr>
							<tr>
								<td>Validade (jun/2010 = 201006)</td>
								<td><input type="text" name="cartao.validade" value="201508"></td>
							</tr>
							<tr>
								<td>Cód. Segurança</td>
								<td><input type="text" name="cartao.codigoSeguranca" value="973"></td>
							</tr>
							<tr>
								<td>Token</td>
								<td><input type="text" name="cartao.token" style="width: 319px;"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>Soft Descriptor</td>
					<td><input type="text" style="width: 250px;" name="pedido.softDescriptor" /></td>
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
								<td>Formato Mensagem:</td>
								<td>
									<input type="radio" name="formatoMensagem" value="requisicaoTransacao">Requisição Transação<br>
									<input type="radio" name="formatoMensagem" value="requisicaoAutorizacao" checked>Requisição Autorização Portador									
								</td>
							</tr>	
							<tr>
								<td>&nbsp;</td>
								<td>
									<input type="checkbox" name="gerarToken" value="gerarToken" />Gerar Token
									<input type="hidden" name="pedido.loja" value="1006993069">
								</td>
							</tr>						
						</table>
					</td>
				</tr>		
				<tr>
					<td>Logradouro</td>
					<td>
						<table border="0">
							<tr>
								<td align="left">CEP:</td>
								<td align="left"><input type="text" name="pedido.avs.cep" value="12345-123" maxlength="9" size="10"/></td>
							</tr>								
							<tr>
								<td align="left">CPF:</td>
								<td align="left"><input type="text" name="pedido.avs.cpf" value="12345678901" maxlength="11" size="12"/></td>
							</tr>								
							<tr>
								<td align="left">Endereço:</td>
								<td align="left"><input type="text" name="pedido.avs.endereco" value="Rua Teste AVS" maxlength="40" size="41"/></td>
							</tr>								
							<tr>
								<td align="left">Número:</td>
								<td align="left"><input type="text" name="pedido.avs.numero" value="123" maxlength="6" size="7"/></td>
							</tr>								
							<tr>
								<td align="left">Complemento:</td>
								<td align="left"><input type="text" name="pedido.avs.complemento" value="Casa" maxlength="9" size="10"/></td>
							</tr>								
							<tr>
								<td align="left">Bairro:</td>
								<td align="left"><input type="text" name="pedido.avs.bairro" value="Vila AVS" maxlength="20" size="21"/></td>
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