package br.com.cbmp.ecommerce.requisicao;

import java.io.FileNotFoundException;
import java.io.IOException;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.pedido.Avs;
import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.Cartao;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;
import br.com.cbmp.ecommerce.pedido.Pedido;
import br.com.cbmp.ecommerce.pedido.Produto;
import br.com.cbmp.ecommerce.util.Produtos;

public class MensagemAutorizacaoDiretaTest extends BaseTestCase {

	public void testToXml() throws FileNotFoundException, IOException {		
		Produto produto = Produtos.todos().iterator().next();
		FormaPagamento formaPagamento = new FormaPagamento(Modalidade.DEBITO, 1, Bandeira.VISA);
		
		Pedido pedido = new Pedido(produto, formaPagamento);
		Cartao cartao = new Cartao("4551870000000183", "201501", "585","123098312837h0982n09sdas09sd809sadasd809");
		pedido.setCartao(cartao);
		pedido.setConfiguracaoTransacao(configuracaoTransacao);
		pedido.setAvs(new Avs());
		
		MensagemAutorizacaoDireta mensagemAutorizacaoDireta = new MensagemAutorizacaoDireta(loja)
			.setPedido(pedido)
			.setTid("1006993069002255A001");

		String xml = mensagemAutorizacaoDireta.toXml();
		assertNotNull(xml);

		escreverParaArquivo(xml, "MensagemAutorizacaoDireta.xml");
		getLogger().info(xml);
	}
	
}
