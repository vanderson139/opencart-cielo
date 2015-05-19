package br.com.cbmp.ecommerce.requisicao;

import br.com.cbmp.ecommerce.contexto.Loja;

public class MensagemDownloadLote extends Mensagem {

	private static final String mensagem = "" +
		"<?xml version=\"1.0\" encoding=\"UTF-8\"?>" +
		"<requisicao-download-retorno-lote versao=\"%1$s\" id=\"%2$s\" >" +
			"<dados-ec>" +
		    	"<numero>%3$d</numero>"+
		    	"<chave>%4$s</chave>"+
		    "</dados-ec>"+
		    "<numero-lote>%5$d</numero-lote>"+
		"</requisicao-download-retorno-lote>";
		
	private long numeroLote;
	
	public MensagemDownloadLote(Loja loja, long numeroLote) {
		super(loja);
		this.numeroLote = numeroLote;
	}

	@Override
	String getTemplate() {
		return mensagem;
	}

	@Override
	Object[] getArgumentos() {
		
		return new Object [] {
				getVersao(),
				getId(),
				getLoja().getNumero(),
				getLoja().getChave(),
				this.numeroLote
		};
	}

}
