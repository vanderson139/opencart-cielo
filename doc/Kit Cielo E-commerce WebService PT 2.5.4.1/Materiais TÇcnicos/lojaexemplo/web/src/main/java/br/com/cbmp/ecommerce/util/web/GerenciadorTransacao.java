package br.com.cbmp.ecommerce.util.web;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.lang.StringUtils;

import br.com.cbmp.ecommerce.contexto.Destino;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.requisicao.Mensagem;
import br.com.cbmp.ecommerce.requisicao.MensagemAutorizacao;
import br.com.cbmp.ecommerce.requisicao.MensagemCancelamento;
import br.com.cbmp.ecommerce.requisicao.MensagemCaptura;
import br.com.cbmp.ecommerce.requisicao.MensagemConsulta;
import br.com.cbmp.ecommerce.requisicao.MensagemConsultaChaveSecundaria;
import br.com.cbmp.ecommerce.requisicao.Operacao;
import br.com.cbmp.ecommerce.requisicao.Requisicao;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;
import br.com.cbmp.ecommerce.resposta.Transacao;

public class GerenciadorTransacao {

	private HttpServletRequest request;
	
	public GerenciadorTransacao(final HttpServletRequest request) {
		this.request = request;
	}
	
	GerenciadorTransacao() {
	}
	
	public String executar() throws FalhaComunicaoException {
		
		Transacao transacao = getTransacao();
		Loja loja = getLoja();
		long valorTaxaEmbarque = Long.parseLong(getParameter("valorOperacao"));
		Mensagem mensagem = contruirMensagem(transacao, loja, valorTaxaEmbarque);
		return requisitar(mensagem);
	}

	String requisitar(Mensagem mensagem) throws FalhaComunicaoException {
		Requisicao requisicao = new Requisicao(mensagem);
		return requisicao.enviarPara(new Destino()).getConteudo();
	}

	private Transacao getTransacao() {
		String tid = getParameter("tid");
		String pedido = getParameter("pedido");

		Transacao transacao = new Transacao(tid , pedido);
		
		return transacao;
	}

	private Loja getLoja() {
		String numeroLojaString = getParameter("numeroLoja");
		Loja loja = Loja.valueOf(Long.valueOf(numeroLojaString));
		return loja;
	}

	private Mensagem contruirMensagem(Transacao transacao, Loja loja, long valorTaxaEmbarque) {
		Operacao operacao = getOperacao();
		
		switch (operacao) {
			case AUTORIZACAO:
				return new MensagemAutorizacao(loja, transacao);
			case CAPTURA:
				String valorString = getParameter("valor");
				
				if (StringUtils.isEmpty(valorString)) {
					valorString = "0";
				}
				
				long valor = Long.valueOf(valorString);
				return new MensagemCaptura(loja, transacao, valor, valorTaxaEmbarque);
			case CANCELAMENTO:

				String vrCanc = request.getParameter("valorCancelar");
				if(vrCanc == null){
					vrCanc = request.getParameter("valor");
				}
				long lVrCanc = vrCanc.trim().equals("") ? 0 : Long.parseLong(vrCanc);
				return new MensagemCancelamento(loja, transacao, lVrCanc );

			case CONSULTA:
				return new MensagemConsulta(loja, transacao);
			case CONSULTA_CH_SEC:
				return new MensagemConsultaChaveSecundaria(loja, transacao);
			default:
				throw new IllegalArgumentException();
		}
	}

	private Operacao getOperacao() {
		String acao = getParameter("acao");
		Operacao operacao = Operacao.valueOf(acao);
		return operacao;
	}
	
	String getParameter(String parameter) {
		return request.getParameter(parameter);
	}
}
