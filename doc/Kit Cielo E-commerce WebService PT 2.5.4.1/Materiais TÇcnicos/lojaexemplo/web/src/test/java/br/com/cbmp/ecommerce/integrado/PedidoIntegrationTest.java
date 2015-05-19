package br.com.cbmp.ecommerce.integrado;

import junit.framework.TestCase;

import org.apache.log4j.Logger;

import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;
import br.com.cbmp.ecommerce.pedido.Pedido;
import br.com.cbmp.ecommerce.pedido.TransacaoService;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;
import br.com.cbmp.ecommerce.resposta.Transacao;
import br.com.cbmp.ecommerce.util.Produtos;

public class PedidoIntegrationTest extends TestCase {
	
	private static final Logger logger = Logger.getLogger(PedidoIntegrationTest.class);
	
	public void testCriar() throws FalhaComunicaoException {		
		FormaPagamento formaPagamento = new FormaPagamento(Modalidade.CREDITO_A_VISTA, 1, Bandeira.VISA);
		
		Pedido pedido = new Pedido(Produtos.todos().iterator().next(), formaPagamento);
		
		Transacao transacao = pedido.criarTransacao();

		logger.info("Transação criada: '" + transacao + "'");
		
	}
	
	public void testCancelar() throws FalhaComunicaoException {
		Transacao transacao = new Transacao("10199074410000261001","123");
		Transacao transacaoCancelada = new TransacaoService(Loja.leituraCartaoCielo()).cancelar(transacao, 0);
		System.out.println(transacaoCancelada.getConteudo());
	}
	

}
