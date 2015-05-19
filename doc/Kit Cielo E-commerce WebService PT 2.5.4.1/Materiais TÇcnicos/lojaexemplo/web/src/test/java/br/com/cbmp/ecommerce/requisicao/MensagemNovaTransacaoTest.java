package br.com.cbmp.ecommerce.requisicao;

import java.io.FileNotFoundException;
import java.io.IOException;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.Cartao;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;
import br.com.cbmp.ecommerce.pedido.Pedido;
import br.com.cbmp.ecommerce.util.Produtos;

public class MensagemNovaTransacaoTest extends BaseTestCase {

	public void testToXml() throws FileNotFoundException, IOException {		
//		Pedido pedido = new Pedido(Produtos.todos().iterator().next(), new FormaPagamento(Modalidade.DEBITO, 1, Bandeira.VISA));
//		pedido.setConfiguracaoTransacao(configuracaoTransacao);
//		
//		MensagemNovaTransacao mensagemNovaTransacao = new MensagemNovaTransacao(loja, pedido);
//
//		String xml = mensagemNovaTransacao.toXml();
//		assertNotNull(xml);
//
//		escreverParaArquivo(xml, "MensagemNovaTransacao.xml");
//		getLogger().info(xml);
	}
	
	public void testComCartaoToXml() throws FileNotFoundException, IOException {		
//		Loja loja = Loja.leituraCartaoLoja();
//		Pedido pedido = new Pedido(Produtos.todos().iterator().next(), new FormaPagamento(Modalidade.PARCELADO_LOJA, 2, Bandeira.VISA));
//		Cartao cartao = new Cartao("4551870000000183", "201501", "585", "123098312837h0982n09sdas09sd809sadasd809");
//		pedido.setCartao(cartao);
//		pedido.setConfiguracaoTransacao(configuracaoTransacao);
//		
//		MensagemNovaTransacao mensagemNovaTransacao = new MensagemNovaTransacao(loja, pedido);
//
//		String xml = mensagemNovaTransacao.toXml();
//		assertNotNull(xml);
//
//		escreverParaArquivo(xml, "MensagemNovaTransacaoCartao.xml");
//		getLogger().info(xml);
	}	

}
