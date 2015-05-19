package br.com.cbmp.ecommerce.requisicao;

import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.resposta.Transacao;

public class MensagemConsultaChaveSecundaria extends Mensagem {

	private Transacao transacao;
	
	private static final String template = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"+
										   "<requisicao-consulta-chsec id=\"%1$s\" versao=\"%2$s\">" +
										   		"<numero-pedido>%3$s</numero-pedido>"+
										   		"<dados-ec>"+
										   			"<numero>%4$s</numero>"+
										   			"<chave>%5$s</chave>"+
										   		"</dados-ec>"+
										   	"</requisicao-consulta-chsec>";


	public MensagemConsultaChaveSecundaria(Loja loja, Transacao transacao) {
		super(loja);
		this.transacao = transacao;
	}

	@Override
	String getTemplate() {
		return template;
	}

	@Override
	Object[] getArgumentos() {		
		
		return new Object [] {
				getId(),
				getVersao(),
				transacao.getDadosPedido().getNumero(),
				getLoja().getNumero(),
				getLoja().getChave()
		};
	}

}
