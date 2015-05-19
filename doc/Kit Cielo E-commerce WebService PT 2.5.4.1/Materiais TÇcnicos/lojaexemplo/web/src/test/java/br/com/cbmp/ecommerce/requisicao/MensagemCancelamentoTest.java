package br.com.cbmp.ecommerce.requisicao;

import java.io.FileNotFoundException;
import java.io.IOException;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.resposta.Transacao;

public class MensagemCancelamentoTest extends BaseTestCase {
	
	public void testToXml() throws FileNotFoundException, IOException {
		Transacao transacao = new Transacao("12345","123");
		
		Mensagem mensagemCancelamento = new MensagemCancelamento(loja, transacao, 0);
		
		escreverParaArquivo(mensagemCancelamento.toXml(), "MensagemCancelamento.xml");
	}
	

}
