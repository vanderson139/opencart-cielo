package br.com.cbmp.ecommerce.requisicao;

import java.util.ArrayList;
import java.util.List;

import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Pedido;

public class MensagemRequisicaoToken extends Mensagem {
	
	private Pedido pedido;
	
	public MensagemRequisicaoToken(Loja loja, Pedido pedido) {
		super(loja);
		this.pedido = pedido;
	}

	
	

	@Override
	String getTemplate() {
		return "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" + 
		"<requisicao-token id=\"%1$s\" versao=\"%2$s\">" + 
			"<dados-ec>" + 
				"<numero>%3$d</numero>" + 
				"<chave>%4$s</chave>" + 
			"</dados-ec>"+
			"<dados-portador>"+
		    	"<numero>%5$s</numero>"+
		    	"<validade>%6$s</validade>"+
//		    	"<indicador>0</indicador>"+
//		    	"<codigo-seguranca>%7$s</codigo-seguranca>"+
		    	"<nome-portador>%8$s</nome-portador>"+
//		    	"<token/>"+
		    "</dados-portador>"+
		"</requisicao-token>";
	}

	@Override
	Object[] getArgumentos() {
		
		List<Object> argumentos = new ArrayList<Object>();
		
		argumentos.add(0, getId());
		argumentos.add(1, getVersao());
		argumentos.add(2, getLoja().getNumero());
		argumentos.add(3, getLoja().getChave());
		argumentos.add(4, pedido.getCartao().getNumero());
		argumentos.add(5, pedido.getCartao().getValidade());
		argumentos.add(6, pedido.getCartao().getCodigoSeguranca());
		argumentos.add(7, pedido.getCartao().getNomePortador());
		
		return argumentos.toArray();
	}

}
