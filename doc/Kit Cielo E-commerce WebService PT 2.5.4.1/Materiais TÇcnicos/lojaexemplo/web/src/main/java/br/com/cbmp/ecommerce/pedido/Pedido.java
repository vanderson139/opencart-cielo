package br.com.cbmp.ecommerce.pedido;

import java.util.Date;

import org.apache.commons.lang.builder.ToStringBuilder;
import org.apache.commons.lang.builder.ToStringStyle;
import org.apache.commons.lang.math.RandomUtils;
import org.apache.commons.lang.time.DateFormatUtils;

import br.com.cbmp.ecommerce.contexto.ConfiguracaoTransacao;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;
import br.com.cbmp.ecommerce.resposta.Transacao;

public class Pedido {

	private String numero;
	
	private String valor;
	
	private Date data;
	
	private Transacao transacao;
	
	private FormaPagamento formaPagamento;
	
	private Loja loja;
	
	private ConfiguracaoTransacao configuracaoTransacao;
	
	private Cartao cartao;
	
	private boolean autorizacaoDireta;
	
	private String descricao;
	
	private String softDescritor;
	
	private int taxaEmbarque;
	
	private Avs avs;
	
	private Celular celular;
	
	private String campoLivre;
	
	private boolean isCelularBuyPage;
	

   public Pedido(Produto produto, FormaPagamento formaPagamento) {
		// identificador qualquer
		this.numero = String.valueOf(RandomUtils.nextInt());
		this.valor = produto.getValor();
		this.formaPagamento = formaPagamento;
		this.data = new Date();
//		this.loja = Loja.leituraCartaoCielo();
	}
	
	public Pedido() {
		super();
		this.numero = String.valueOf(RandomUtils.nextInt());
	}

	public Transacao criarTransacao() throws FalhaComunicaoException {
		transacao = getTransacaoService().criarTransacao(this);
		return transacao;
	}
	
	public Transacao criarToken() throws FalhaComunicaoException {
		transacao = getTransacaoService().criarToken(this);
		return transacao;
	}
	
	public Transacao cancelarTransacao(long valorCancelamento) throws FalhaComunicaoException {
		transacao = getTransacaoService().cancelar(transacao, valorCancelamento);
		return transacao;
	}
	
	public Transacao capturarTransacao(long valor, long valorTaxaEmbarque) throws FalhaComunicaoException {
		transacao = getTransacaoService().capturar(transacao, valor, valorTaxaEmbarque);
		return transacao;		
	}
	
	public Transacao consultarTransacao() throws FalhaComunicaoException {
		transacao = getTransacaoService().consultar(transacao);
		return transacao;		
	}
	
	public boolean finalizar() throws FalhaComunicaoException {
		if (autorizacaoDireta) {
			return isPedidoFinalizado();
		}
		
		transacao = getTransacaoService().consultar(transacao);
		return isPedidoFinalizado();
	}
	
	public boolean finalizarComAutorizacaoDireta() throws FalhaComunicaoException {
		autorizacaoDireta = true;
		transacao = getTransacaoService().autorizarDireto(this);
		return isPedidoFinalizado();		
	}

	private boolean isPedidoFinalizado() {
		StatusTransacao statusTransacao = StatusTransacao.valueOf(transacao.getStatus());
		boolean pedidoOk = statusTransacao.isAutorizada() || statusTransacao.isCapturada();
		return pedidoOk;
	}
	
	public Transacao autorizarTransacao() throws FalhaComunicaoException {
		transacao = getTransacaoService().autorizar(transacao);
		return transacao;		
	}

	public String getNumero() {
		return numero;
	}

	public String getValor() {
		return valor;
	}

	public Date getData() {
		return data;
	}
	
	public String getDataFormatada() {
		return DateFormatUtils.format(data, "dd/MM/yyyy HH:mm:ss");
	}
	
	public Transacao getTransacao() {
		return transacao;
	}

	public FormaPagamento getFormaPagamento() {
		return formaPagamento;
	}

	@Override
	public String toString() {
		return ToStringBuilder.reflectionToString(this, ToStringStyle.MULTI_LINE_STYLE);
	}

	private TransacaoService getTransacaoService() {
		return new TransacaoService(loja);
	}

	public ConfiguracaoTransacao getConfiguracaoTransacao() {
		// caso não esteja definido, retorna a configuração default
		return configuracaoTransacao != null ? configuracaoTransacao : new ConfiguracaoTransacao();
	}

	public void setConfiguracaoTransacao(ConfiguracaoTransacao configuracaoTransacao) {
		this.configuracaoTransacao = configuracaoTransacao;
	}

	public Cartao getCartao() {
		return cartao;
	}
	
	public void setCartao(final Cartao cartao) {
		if (cartao != null) {
			this.cartao = cartao;
			this.loja = Loja.leituraCartaoLoja();
		}
		else{
			this.loja = Loja.leituraCartaoLoja();
		}
			
	}

	public boolean temCartao() {
		return cartao != null;
	}
	
	public boolean temCelular() {
		return celular != null;
	}

	
	public String getDescricao() {
		return descricao;
	}

	public void setDescricao(String descricao) {
		this.descricao = descricao;
	}

	public void setLoja(Loja loja) {
		this.loja = loja;
	}

	public void setSoftDescritor(String softDescritor) {
		this.softDescritor = softDescritor;
	}

	public String getSoftDescritor() {
		return softDescritor;
	}

	public int getTaxaEmbarque() {
		return taxaEmbarque;
	}

	public void setTaxaEmbarque(int taxaEmbarque) {
		this.taxaEmbarque = taxaEmbarque;
	}

	public Avs getAvs() {
		return avs;
	}

	public void setAvs(Avs avs) {
		this.avs = avs;
	}
	
	public Celular getCelular() {
		return celular;
	}

	public void setCelular(Celular celular) {
		this.celular = celular;
	}

	public String getCampoLivre() {
		return campoLivre;
	}

	public void setCampoLivre(String campoLivre) {
		this.campoLivre = campoLivre;
	}

	public boolean isCelularBuyPage() {
		return isCelularBuyPage;
	}

	public void setCelularBuyPage(boolean isCelularBuyPage) {
		this.isCelularBuyPage = isCelularBuyPage;
	}
}
