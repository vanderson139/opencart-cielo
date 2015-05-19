package br.com.cbmp.ecommerce.requisicao;

import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.resposta.Transacao;

public class MensagemCancelamento extends Mensagem {
	
	private Transacao transacao;
	
	private long valorCancelamento;
	
	private static final String template = "" +
		"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" + 
		"<requisicao-cancelamento id=\"%1$s\" versao=\"%2$s\">" +
		"	<tid>%5$s</tid>" +		
		"	<dados-ec>" + 
		"		<numero>%3$d</numero>" + 
		"		<chave>%4$s</chave>" + 
		"	</dados-ec>" + 		 		
		"   <valor>%6$s</valor>"+
		"</requisicao-cancelamento>";		

	public MensagemCancelamento(Loja loja, Transacao transacao, long valorCancelamento) {
		super(loja);
		this.transacao = transacao;
		this.valorCancelamento = valorCancelamento;
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
				transacao.getTid(),
				valorCancelamento
				
			};
		return argumentos;
	}

}
