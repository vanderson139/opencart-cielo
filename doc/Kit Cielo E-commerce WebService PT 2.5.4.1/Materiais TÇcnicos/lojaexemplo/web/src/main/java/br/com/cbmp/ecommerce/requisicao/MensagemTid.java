package br.com.cbmp.ecommerce.requisicao;

import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;

public class MensagemTid extends Mensagem {
	
	private static final String template = "" +
	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" + 
	"<requisicao-tid id=\"%1$s\" versao=\"%2$s\">" +
		"<dados-ec>" + 
			"<numero>%3$d</numero>" + 
			"<chave>%4$s</chave>" + 
		"</dados-ec>" +
		"<forma-pagamento>" +
			"<bandeira>%5$s</bandeira>" +
			"<produto>%6$s</produto>" + 
			"<parcelas>%7$s</parcelas>" + 
		"</forma-pagamento>" + 
	"</requisicao-tid>";	

	private FormaPagamento formaPagamento;

	public MensagemTid(Loja loja, FormaPagamento formaPagamento) {
		super(loja);
		this.formaPagamento = formaPagamento;
	}

	@Override
	Object[] getArgumentos() {
		return new Object [] {
			getId(),
			getVersao(),
			getLoja().getNumero(),
			getLoja().getChave(),
			formaPagamento.getBandeira(),
			formaPagamento.getModalidade().getCodigo(),
			formaPagamento.getParcelas(),
		};
	}

	@Override
	String getTemplate() {
		return template;
	}

}
