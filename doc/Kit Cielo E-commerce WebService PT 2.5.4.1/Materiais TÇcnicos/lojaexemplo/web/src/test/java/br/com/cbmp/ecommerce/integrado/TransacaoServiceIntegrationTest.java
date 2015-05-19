package br.com.cbmp.ecommerce.integrado;

import org.apache.commons.lang.time.StopWatch;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.Cartao;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;
import br.com.cbmp.ecommerce.pedido.Pedido;
import br.com.cbmp.ecommerce.pedido.TransacaoService;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;
import br.com.cbmp.ecommerce.resposta.Transacao;
import br.com.cbmp.ecommerce.util.Produtos;

public class TransacaoServiceIntegrationTest extends BaseTestCase {
	
	public void testAutorizacaoDireta() throws FalhaComunicaoException {
		FormaPagamento formaPagamento = new FormaPagamento(Modalidade.PARCELADO_ADMINISTRADORA, 3, Bandeira.VISA);
		Pedido pedido = new Pedido(Produtos.todos().iterator().next(), formaPagamento);
		Cartao cartao = new Cartao("4551870000000183", "201501", "585", "123098312837h0982n09sdas09sd809sadasd809");
		pedido.setCartao(cartao);
		
		TransacaoService service = new TransacaoService(Loja.leituraCartaoLoja());
		
		StopWatch cronometro = new StopWatch();
		cronometro.start();
		
		Transacao transacao = service.autorizarDireto(pedido);
		
		cronometro.stop();
		
		getLogger().info("Resposta (" + cronometro + ") '" + transacao + "'");
	}

}
