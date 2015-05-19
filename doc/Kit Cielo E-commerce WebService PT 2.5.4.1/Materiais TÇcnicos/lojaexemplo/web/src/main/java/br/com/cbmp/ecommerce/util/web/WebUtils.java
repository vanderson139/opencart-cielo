package br.com.cbmp.ecommerce.util.web;

import java.math.BigDecimal;

import javax.servlet.http.HttpServletRequest;

import org.apache.log4j.Logger;

import br.com.cbmp.ecommerce.contexto.ConfiguracaoTransacao;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Avs;
import br.com.cbmp.ecommerce.pedido.Cartao;
import br.com.cbmp.ecommerce.pedido.Celular;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Lote;
import br.com.cbmp.ecommerce.pedido.Pedido;
import br.com.cbmp.ecommerce.pedido.Produto;
import br.com.cbmp.ecommerce.util.Lotes;
import br.com.cbmp.ecommerce.util.Pagamento;
import br.com.cbmp.ecommerce.util.Pedidos;
import br.com.cbmp.ecommerce.util.Produtos;

public class WebUtils {
	
	private static final Logger logger = Logger.getLogger(WebUtils.class);
	
	private HttpServletRequest request;
	
	private Pedidos pedidos;
	
	private Lotes lotes;
	
	public WebUtils(HttpServletRequest request) {
		this.request = request;
		pedidos = (Pedidos) request.getSession().getAttribute("pedidos");
		lotes = (Lotes) request.getSession().getAttribute("lotes");
	}
	
	public void RemovePedidos(HttpServletRequest request){
		request.getSession().removeAttribute("pedidos");
	}
	
	public Pedido criarPedido() throws Exception {
		
		Produto produto = getProduto();
		FormaPagamento formaPagamento = getFormaPagamento();
		String formatoMensagem = request.getParameter("formatoMensagem");
		Pedido pedido = new Pedido(produto, formaPagamento);
		pedido.setConfiguracaoTransacao(getConfiguracaoTransacao());
		
		if(formatoMensagem != null){
			if(formatoMensagem.equals("requisicaoTransacao") || formatoMensagem.equals("requisicaoAutorizacao")){
				pedido.setCartao(getCartao());
			}else{
				if (formatoMensagem.equals("requisicaoTransacaoCelular")){
					pedido.setCelular(getCelular());
				}else{
					if(formatoMensagem.equals("requisicaoTransacaoCelularCielo")){
						pedido.setCelularBuyPage(true);
					}
				}
			}
		}else{
			throw new Exception("O Tipo da requisição não foi informado!");
		}
		pedido.setDescricao("[origem:" + request.getRemoteAddr() + "]");		
		
		pedido.setSoftDescritor(request.getParameter("pedido.softDescriptor"));
		String taxaEmbarque = request.getParameter("comTaxaEmbarque");
		
		if(taxaEmbarque!=null && taxaEmbarque.equals("comTaxaEmbarque") && (formatoMensagem.equals("requisicaoTransacao") || formatoMensagem.equals("requisicaoAutorizacao"))){
			if(request.getParameter("pedido.taxaEmbarque") != null && request.getParameter("pedido.taxaEmbarque").toString().matches("^[0-9]*$")) {
				pedido.setTaxaEmbarque(Integer.parseInt(request.getParameter("pedido.taxaEmbarque")));
			}
			else {
				throw new Exception("O campo \"Taxa de Embarque\" não deve conter letras!");
			}
		}
				
		
		if (Long.parseLong(request.getParameter("pedido.loja")) == Loja.leituraCartaoCielo().getNumero())
			pedido.setLoja(Loja.leituraCartaoCielo());
		else
			pedido.setLoja(Loja.leituraCartaoLoja());
		
		if(request.getParameter("pedido.avs.cep") != null) {
			pedido.setAvs(new Avs());
			pedido.getAvs().setCep(request.getParameter("pedido.avs.cep"));
			pedido.getAvs().setCpf(request.getParameter("pedido.avs.cpf"));
			pedido.getAvs().setEndereco(request.getParameter("pedido.avs.endereco"));
			pedido.getAvs().setNumero(request.getParameter("pedido.avs.numero"));
			pedido.getAvs().setComplemento(request.getParameter("pedido.avs.complemento"));
			pedido.getAvs().setBairro(request.getParameter("pedido.avs.bairro"));
		}	
		
		armazenarPedido(pedido);
		
		if (logger.isDebugEnabled()) {
			logger.debug("Pedido criado " + pedido);
		}
		
		
		
		
		return pedido;
	}
	
	public Pedido criarRequisicaoToken(){

		Pedido pedido = new Pedido();
		pedido.setCartao(getCartao());
		armazenarPedido(pedido);
		
		return pedido;
	}
	
	public Cartao getCartao() {
		
		
		String numeroCartao = request.getParameter("cartao.numero");
		
		Cartao cartao = null;
		
		if (numeroCartao == null) {
			String token = request.getParameter("cartao.token");
			if(token != null && !token.equals("")){
				cartao = new Cartao("", "", "", token);
			}
		} else {
			String validade = request.getParameter("cartao.validade");
			String codigoSeguranca = request.getParameter("cartao.codigoSeguranca");
			String token = request.getParameter("cartao.token");
			cartao = new Cartao(numeroCartao, validade, codigoSeguranca, token);
			
			if(request.getParameter("cartao.nomePortador") != null){
				cartao.setNomePortador(request.getParameter("cartao.nomePortador")); 
			}
		}
		return cartao;
	}	
	
	public Pedido recuperarUltimoPedido() {
		String numeroPedido = (String) request.getSession().getAttribute("numeroPedido");

		if (numeroPedido == null) {
			throw new IllegalStateException("Pedido nao encontrado!");
		}
		
		return pedidos.recuperar(numeroPedido);
	}
	
	public Pedidos getPedidos() {
		return pedidos;
	}

	private Produto getProduto() {
		String idProduto = request.getParameter("produto");
		Produto produto = Produtos.recuperar(Long.valueOf(idProduto));

		String valor = String.valueOf(BigDecimal.ZERO);
		if(request.getParameter("prodValor") != null)
			valor = request.getParameter("prodValor");
		produto.setValor(String.valueOf(valor));
		
		return produto;
	}

	private FormaPagamento getFormaPagamento() {
		String frPagamento = request.getParameter("formaPagamento");
		String tipoParcelamento = request.getParameter("tipoParcelamento");		
		String formatoMensagem = request.getParameter("formatoMensagem");
		String codigoBandeira = "";
		boolean isCelular = false;
		
		if(formatoMensagem!= null){
			if(formatoMensagem.equals("requisicaoTransacaoCelular") || formatoMensagem.equals("requisicaoTransacaoCelularCielo")){
				isCelular = true;
			}
		}
		
		if (isCelular){
			codigoBandeira = "9";//Bandeira fixa para pagamento com celular.
		}else{
			codigoBandeira = request.getParameter("codigoBandeira");
		}
		FormaPagamento formaPagamento = Pagamento.inferirFormaPagamento(frPagamento, tipoParcelamento, codigoBandeira);
		return formaPagamento;
	}
	
	private void armazenarPedido(Pedido pedido) {
		pedidos.adicionar(pedido);
		request.getSession().setAttribute("numeroPedido", pedido.getNumero());
	}
	
	private ConfiguracaoTransacao getConfiguracaoTransacao() {		
		String indAutorizacao = "";
		String capturarAutomaticamente = request.getParameter("capturarAutomaticamente");
		String formatoMensagem = request.getParameter("formatoMensagem");
		if(formatoMensagem=="requisicaoTransacaoCelular"){
			indAutorizacao = "1";//Autorizar transação somente se autenticada
		}
		else{
			indAutorizacao = request.getParameter("indicadorAutorizacao");
		}
		
		ConfiguracaoTransacao configuracaoTransacao;
		
		if (indAutorizacao != null) {
			configuracaoTransacao = new ConfiguracaoTransacao(indAutorizacao, capturarAutomaticamente);
		}
		else {
			configuracaoTransacao = new ConfiguracaoTransacao(capturarAutomaticamente);
		}
		
		configuracaoTransacao.setUrlRetorno(getUrlRetorno());
		
		configuracaoTransacao.setIdioma(getIdioma());
		
		configuracaoTransacao.setGerarToken(request.getParameter("gerarToken") != null);
		
		configuracaoTransacao.setComTaxaEmbarque(request.getParameter("comTaxaEmbarque") != null);
		
		return configuracaoTransacao;
	}

	private String getIdioma() {
		if(request.getParameterMap().containsKey("idioma")){
			return request.getParameter("idioma");
		}
		return "PT";
	}

	public String getUrlRetorno() {
		if(request.getParameterMap().containsKey("urlRetorno")){
			return request.getParameter("urlRetorno");
		} else {
			String scheme = request.getScheme();
			String localAddress = null;
			
			if("127.0.0.1".equals(request.getLocalAddr())){
				localAddress = "localhost";
			} else {
				localAddress = request.getLocalAddr();
			}
			
			int localPort = request.getLocalPort();
			String contextPath = request.getContextPath();
			 
			StringBuilder builder = new StringBuilder();
			builder
				.append(scheme)
				.append("://")
				.append(localAddress)
				.append(":")
				.append(localPort)
				.append(contextPath)
				.append("/retorno.jsp");
			return builder.toString();
		}
	}
	
	public boolean isRequisicaoTransacao(){
		String formatoMensagem = this.request.getParameter("formatoMensagem"); 
		return ( formatoMensagem != null && (formatoMensagem.equals("requisicaoTransacao") || formatoMensagem.equals("requisicaoTransacaoCelular")));						
	}
	
	public Lotes getLotes() {
		return lotes;
	}

	public Lote criarDownloadLote(){
		Loja loja = Loja.leituraCartaoLoja();
		long numeroLote = new Long(this.request.getParameter("numeroLote"));
		return new Lote(loja, numeroLote);
	}
	
	public void armazenarLote(Lote lote){
		lotes.adicionar(lote);
		request.getSession().setAttribute("numeroLote", String.valueOf(lote.getNumeroLote()));
	}

	public Lote recuperarUltimoLote() {
		String numeroLote = (String) request.getSession().getAttribute("numeroLote");

		if (numeroLote == null) {
			throw new IllegalStateException("Lote nao encontrado!");
		}
		
		return lotes.recuperar(Long.valueOf(numeroLote).longValue());
	}
	/**
	 * Metodo que valida a integridade da informação do campo.
	 * @param nomeCampo -> Nome do campo para mostrar na exceção
	 * @param valorValidado -> Valor do campo que será validado
	 * @param validaNull -> TRUE para validar se é null, False não valida
	 * @param validaSomenteNumero -> TRUE para validar se o valor contém somente números, False não valida
	 * @param tamanhoMinimo -> o tamanho mínimo que a string deve conter. 0 para não validar tamanho
	 * @param tamanhoMaximo -> o tamanho máximo que a string deve conter. 0 para não validar tamanho
	 * @throws Exception
	 */
	public void validador(final String nomeCampo, final String valorValidado, final boolean validaNull, final boolean validaSomenteNumero, final int tamanhoMinimo, final int tamanhoMaximo) throws Exception{
			
		if (validaNull){
			if (valorValidado == null || valorValidado.length() == 0){
				throw new Exception(String.format("Obrigatório o preenchimento do campo %s!", nomeCampo));
			}
		}
		
		if(validaSomenteNumero){
			if (!valorValidado.matches("^[0-9]*$")){
				throw new Exception(String.format("O campo %s deve conter somente números!",nomeCampo));
			}
		}
		 
		if(tamanhoMinimo != 0 && tamanhoMaximo != 0){
			if(valorValidado.length() < tamanhoMinimo || valorValidado.length() > tamanhoMaximo){
				throw new Exception(String.format("O campo %s não tem o tamanho esperado!",nomeCampo));
			}
		}

	}
	/**
	 *  Método que monta um objeto Celular com os dados da página da loja exemplo
	 * @return
	 * @throws Exception 
	 */
	public Celular getCelular() throws Exception{
		Celular celular = new Celular();
	
		this.validador("DDD",request.getParameter("celular.ddd").toString() , true, true, 2, 2);
		celular.setDdd(request.getParameter("celular.ddd"));
		
		this.validador("Numero Celular",request.getParameter("celular.numero").toString() , true, true, 8, 9);
		celular.setNumero(request.getParameter("celular.numero"));		
		
		return celular;
	}
	
}
