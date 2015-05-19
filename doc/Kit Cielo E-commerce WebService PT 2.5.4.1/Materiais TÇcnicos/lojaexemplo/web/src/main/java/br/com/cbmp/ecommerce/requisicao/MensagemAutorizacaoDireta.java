package br.com.cbmp.ecommerce.requisicao;

import br.com.cbmp.ecommerce.contexto.ConfiguracaoTransacao;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Cartao;
import br.com.cbmp.ecommerce.pedido.Pedido;

public class MensagemAutorizacaoDireta extends Mensagem {
	
	private static final String template = "" +
	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" + 
	"<requisicao-autorizacao-portador id=\"%1$s\" versao=\"%2$s\">" +
		"<tid>%3$s</tid>" +
		"<dados-ec>" + 
			"<numero>%4$d</numero>" + 
			"<chave>%5$s</chave>" + 
		"</dados-ec>" +
		"<dados-cartao>" +
			"<numero>%6$s</numero>" +
			"<validade>%7$s</validade>" +
			"<indicador>%8$d</indicador>" +
			"<codigo-seguranca>%9$s</codigo-seguranca>" +
		"</dados-cartao>" +
		"<dados-pedido>" + 
			"<numero>%10$s</numero>" + 
			"<valor>%11$s</valor>" + 
			"<moeda>%12$03d</moeda>" + 
			"<data-hora>%13$tY-%13$tm-%13$tdT%13$tH:%13$tM:%13$tS</data-hora>" +
			"<descricao>%17$s</descricao>" +			
			"<idioma>PT</idioma>" +
			"%18$s" +
			"</dados-pedido>" + 
		"<forma-pagamento>" +
			"<bandeira>%14$s</bandeira>" +
			"<produto>%15$s</produto>" + 
			"<parcelas>%16$s</parcelas>" + 
		"</forma-pagamento>" + 
		"<capturar-automaticamente>%19$s</capturar-automaticamente>" + 
		"<avs>" +
			"<![CDATA[" +
				"<dados-avs>" +
					"<cpf>%20$s</cpf>" +
					"<endereco>%21$s</endereco>" +
					"<complemento>%22$s</complemento>" +
					"<numero>%23$s</numero>" +
					"<bairro>%24$s</bairro>" +
					"<cep>%25$s</cep>" +
				"</dados-avs>]]>" +
		"</avs>" +
	"</requisicao-autorizacao-portador>";		

	private Pedido pedido;
	
	private String tid;
	
	public MensagemAutorizacaoDireta(Loja loja) {
		super(loja);
	}

	@Override
	Object[] getArgumentos() {
		Cartao cartao = pedido.getCartao();
		ConfiguracaoTransacao configuracaoTransacao = pedido.getConfiguracaoTransacao();
		
		return new Object [] {
			getId(),//0
			getVersao(),//1
			
			tid, //2
			
			getLoja().getNumero(), //3
			getLoja().getChave(), //4
			
			cartao.getNumero(), //5
			cartao.getValidade(), //6
			cartao.getIndicadorCodigoSeguranca().getCodigo(), //7
			cartao.getCodigoSeguranca(), //8
			
			pedido.getNumero(), //9
			pedido.getValor(), //10
			986, //11
			pedido.getData(), //12
			pedido.getFormaPagamento().getBandeira().getNome(), //13
			pedido.getFormaPagamento().getModalidade().getCodigo(), //14
			pedido.getFormaPagamento().getParcelas(), //15
			pedido.getDescricao(), //16
			configuracaoTransacao.isComTaxaEmbarque() ? 
					"<taxa-embarque>" + String.valueOf(pedido.getTaxaEmbarque()) +"</taxa-embarque>" : "" , //17
			configuracaoTransacao.isCapturarAutomaticamente(), //18
			pedido.getAvs().getCpf(), //19
			pedido.getAvs().getEndereco(), //20
			pedido.getAvs().getComplemento(), //21
			pedido.getAvs().getNumero(), //22
			pedido.getAvs().getBairro(), //23
			pedido.getAvs().getCep()//24
		};
	}

	@Override
	String getTemplate() {
		return template;
	}

	public MensagemAutorizacaoDireta setPedido(Pedido pedido) {
		this.pedido = pedido;
		return this;
	}

	public MensagemAutorizacaoDireta setTid(String tid) {
		this.tid = tid;
		return this;
	}
}
