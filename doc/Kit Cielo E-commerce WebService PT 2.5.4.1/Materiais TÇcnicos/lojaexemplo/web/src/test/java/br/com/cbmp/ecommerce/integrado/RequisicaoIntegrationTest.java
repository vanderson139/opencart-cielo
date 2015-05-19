package br.com.cbmp.ecommerce.integrado;

import junit.framework.TestCase;

import org.apache.log4j.Logger;

import br.com.cbmp.ecommerce.contexto.ConfiguracaoTransacao;
import br.com.cbmp.ecommerce.contexto.Destino;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;
import br.com.cbmp.ecommerce.pedido.Pedido;
import br.com.cbmp.ecommerce.requisicao.MensagemCancelamento;
import br.com.cbmp.ecommerce.requisicao.MensagemNovaTransacao;
import br.com.cbmp.ecommerce.requisicao.Requisicao;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;
import br.com.cbmp.ecommerce.resposta.Transacao;
import br.com.cbmp.ecommerce.util.Produtos;

public class RequisicaoIntegrationTest extends TestCase {
	
	private static final Logger logger = Logger.getLogger(RequisicaoIntegrationTest.class);
	
	public void testRequisicao() throws FalhaComunicaoException {
		Loja loja = Loja.leituraCartaoCielo();
		FormaPagamento formaPagamento = new FormaPagamento(Modalidade.PARCELADO_ADMINISTRADORA, 3, Bandeira.VISA);
		Pedido pedido = new Pedido(Produtos.todos().iterator().next(), formaPagamento);
		pedido.setConfiguracaoTransacao(new ConfiguracaoTransacao());
		
		MensagemNovaTransacao mensagemNovaTransacao = new MensagemNovaTransacao(loja, pedido);
		Requisicao requisicao = new Requisicao(mensagemNovaTransacao);
		
		Destino destino = new Destino();
		Transacao transacao = requisicao.enviarPara(destino);
		
		logger.info(transacao);
		
		requisicao = new Requisicao(new MensagemCancelamento(loja, transacao, 0));
		transacao = requisicao.enviarPara(destino);
		
		logger.info("cancelada " + transacao);
		
	}

}
