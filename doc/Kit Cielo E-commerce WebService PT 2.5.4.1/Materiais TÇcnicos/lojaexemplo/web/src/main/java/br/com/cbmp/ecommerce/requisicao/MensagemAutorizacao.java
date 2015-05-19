package br.com.cbmp.ecommerce.requisicao;

import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.resposta.Transacao;

public class MensagemAutorizacao extends Mensagem {
	
	private Transacao transacao;
	
	private static final String template = "" +
		"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" + 
		"<requisicao-autorizacao-tid id=\"%1$s\" versao=\"%2$s\">" +
		"	<tid>%5$s</tid>" +		
		"	<dados-ec>" + 
		"		<numero>%3$d</numero>" + 
		"		<chave>%4$s</chave>" + 
		"	</dados-ec>" + 		 
		"</requisicao-autorizacao-tid>";		

	public MensagemAutorizacao(Loja loja, Transacao transacao) {
		super(loja);
		this.transacao = transacao;
	}

	@Override	
	String getTemplate() {
		return template;
	}

	@Override
	Object[] getArgumentos() {
		Object [] argumentos = { 
				getId(),
				getVersao(),
				getLoja().getNumero(),
				getLoja().getChave(),
				transacao.getTid()
				
			};
		return argumentos;
	}

}
