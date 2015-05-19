package br.com.cbmp.ecommerce.resposta;

import java.util.List;

import org.apache.commons.lang.builder.ToStringBuilder;
import org.apache.commons.lang.builder.ToStringStyle;

import br.com.cbmp.ecommerce.pedido.Celular;
import br.com.cbmp.ecommerce.pedido.StatusTransacao;

public class Transacao extends Resposta {

	class FormaPagamento {
		String bandeira;
		String produto;
		short parcelas;
	}

	public class DadosPedido {
		String numero;
		long valor;
		short moeda;
		String dataHora;
		String idioma;
		String descricao;
		String taxaEmbarque;

		public String getNumero() {
			return numero;
		}

		public long getValor() {
			return valor;
		}
	}

	public class Processamento {
		short codigo;
		String mensagem;
		String dataHora;
		long valor;
	}

	public class Autenticacao extends Processamento {
		short eci;
	}

	public class Autorizacao extends Processamento {
		String lr;
		String codigoAutorizacao;
		String arp;
		String nsu;
		String codigoAvsCep;
		public String getCodigoAvsCep() {
			return codigoAvsCep;
		}

		public void setCodigoAvsCep(String codigoAvsCep) {
			this.codigoAvsCep = codigoAvsCep;
		}

		public String getMensagemAvsCep() {
			return mensagemAvsCep;
		}

		public void setMensagemAvsCep(String mensagemAvsCep) {
			this.mensagemAvsCep = mensagemAvsCep;
		}

		public String getCodigoAvsEnd() {
			return codigoAvsEnd;
		}

		public void setCodigoAvsEnd(String codigoAvsEnd) {
			this.codigoAvsEnd = codigoAvsEnd;
		}

		public String getMensagemAvsEnd() {
			return mensagemAvsEnd;
		}

		public void setMensagemAvsEnd(String mensagemAvsEnd) {
			this.mensagemAvsEnd = mensagemAvsEnd;
		}

		String mensagemAvsCep;
		String codigoAvsEnd;
		String mensagemAvsEnd;
		
		public String getLr() {
			return lr;
		}

		public String getCodigoAutorizacao() {
			return codigoAutorizacao;
		}

		public String getArp() {
			return arp;
		}

		public String getNsu() {
			return nsu;
		}

	}

	public class Token extends Processamento {
		private DadosToken dadosToken;
		private Erro erro;

		public DadosToken getDadosToken() {
			return dadosToken;
		}

		public void setDadosToken(DadosToken dadosToken) {
			this.dadosToken = dadosToken;
		}

		public void setErro(Erro erro) {
			this.erro = erro;
		}

		public Erro getErro() {
			return erro;
		}
	}

	public class DadosToken {

		private String codigoToken;
		private String numeroCartaoTruncado;
		private char status;

		public String getCodigoToken() {
			return codigoToken;
		}

		public void setCodigoToken(String codigoToken) {
			this.codigoToken = codigoToken;
		}

		public String getNumeroCartaoTruncado() {
			return numeroCartaoTruncado;
		}

		public void setNumeroCartaoTruncado(String numeroCartaoTruncado) {
			this.numeroCartaoTruncado = numeroCartaoTruncado;
		}

		public char getStatus() {
			return status;
		}

		public void setStatus(char status) {
			this.status = status;
		}

	}
	
	public class DadosPortador {
		
		private String numero;
		private int validade;
		private String codigoSeguranca;
		private String token;
		
		public String getNumero() {
			return numero;
		}
		
		public void setNumero(String numero) {
			this.numero = numero;
		}
		
		public int getValidade() {
			return validade;
		}
		
		public void setValidade(int validade) {
			this.validade = validade;
		}
		
		public String getCodigoSeguranca() {
			return codigoSeguranca;
		}

		public void setCodigoSeguranca(String codigoSeguranca) {
			this.codigoSeguranca = codigoSeguranca;
		}

		public void setToken(String token) {
			this.token = token;
		}

		public String getToken() {
			return token;
		}
	}
	//Classe para tratar transação com número de celular
	public class DadosPortadorCelular {
		
		private Celular celular;

		public Celular getCelular() {
			return celular;
		}

		public void setCelular(Celular celular) {
			this.celular = celular;
		}
		
	}
	
	public class Captura extends Processamento {
		
		private int valorTaxaEmbarque;

		public void setValorTaxaEmbarque(int valorTaxaEmbarque) {
			this.valorTaxaEmbarque = valorTaxaEmbarque;
		}

		public int getValorTaxaEmbarque() {
			return valorTaxaEmbarque;
		}
	}

	public class Cancelamento extends Processamento {
	}

	private String urlAutenticacao;

	private String tid;

	private short status;

	private Autorizacao autorizacao;

	private Autenticacao autenticacao;

	private Cancelamento cancelamento;

	private List<Cancelamento> cancelamentos;
	
	private Captura captura;

	private DadosPedido dadosPedido;

	private FormaPagamento formaPagamento;
	
	private DadosPortador dadosPortador;

	private DadosPortadorCelular dadosPortadorCelular;

	private String pan;

	private Token token;

	public Transacao(String tid, String pedido) {
		this.tid = tid;
		this.dadosPedido = this.obterDadosPedido(pedido);
	}

	public DadosPedido obterDadosPedido(String pedido) {
		DadosPedido dadosPedido = new DadosPedido();
		dadosPedido.numero = pedido;

		return dadosPedido;
	}

	public String toString() {
		return ToStringBuilder.reflectionToString(this,
				ToStringStyle.MULTI_LINE_STYLE);
	}

	public String getUrlAutenticacao() {
		return urlAutenticacao;
	}

	public String getTid() {
		return tid;
	}

	public short getStatus() {
		return status;
	}

	public StatusTransacao getStatusTransacao() {
		return StatusTransacao.valueOf(getStatus());
	}

	public Autorizacao getAutorizacao() {
		return autorizacao;
	}

	public DadosPedido getDadosPedido() {
		return dadosPedido;
	}

	public FormaPagamento getFormaPagamento() {
		return formaPagamento;
	}

	protected Autenticacao getAutenticacao() {
		return autenticacao;
	}

	protected String getPan() {
		return pan;
	}

	public Cancelamento getCancelamento() {
		return cancelamento;
	}

	public Captura getCaptura() {
		return captura;
	}

	public Token getToken() {
		return token;
	}

	public void setToken(Token token) {
		this.token = token;
	}

	public void setDadosPortador(DadosPortador dadosPortador) {
		this.dadosPortador = dadosPortador;
	}

	public DadosPortador getDadosPortador() {
		return dadosPortador;
	}

	public void setCancelamentos(List<Cancelamento> cancelamentos) {
		this.cancelamentos = cancelamentos;
	}

	public List<Cancelamento> getCancelamentos() {
		return cancelamentos;
	}

	public DadosPortadorCelular getDadosPortadorCelular() {
		return dadosPortadorCelular;
	}

	public void setDadosPortadorCelular(DadosPortadorCelular dadosPortadorCelular) {
		this.dadosPortadorCelular = dadosPortadorCelular;
	}

}
