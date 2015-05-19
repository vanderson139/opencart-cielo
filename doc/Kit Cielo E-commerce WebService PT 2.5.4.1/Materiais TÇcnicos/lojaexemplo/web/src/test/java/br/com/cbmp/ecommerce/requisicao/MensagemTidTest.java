package br.com.cbmp.ecommerce.requisicao;

import java.io.FileNotFoundException;
import java.io.IOException;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;

public class MensagemTidTest extends BaseTestCase {
	
	public void testToXml() throws FileNotFoundException, IOException {		
		FormaPagamento formaPagamento = new FormaPagamento(Modalidade.DEBITO, 1, Bandeira.VISA);
		
		MensagemTid mensagem = new MensagemTid(loja, formaPagamento);

		String xml = mensagem.toXml();
		assertNotNull(xml);

		escreverParaArquivo(xml, "MensagemTid.xml");
		getLogger().info(xml);
	}

}
