package br.com.cbmp.ecommerce.requisicao;

import java.util.ArrayList;
import java.util.List;

import br.com.cbmp.ecommerce.contexto.ConfiguracaoTransacao;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Cartao;
import br.com.cbmp.ecommerce.pedido.Celular;
import br.com.cbmp.ecommerce.pedido.Pedido;

public class MensagemNovaTransacao extends Mensagem {
	
	private static final String parte_1 = "" +
	"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" + 
	"<requisicao-transacao id=\"%1$s\" versao=\"%2$s\">" + 
		"<dados-ec>" + 
		"	<numero>%3$d</numero>" + 
		"	<chave>%4$s</chave>" + 
		"</dados-ec>";
	
	private static final String parte_2 = "" +
		"<dados-portador>" +
		"	<numero>%21$s</numero>" +
		"	<validade>%22$s</validade>" +
		"	<indicador>%23$d</indicador>" +
		"	<codigo-seguranca>%24$s</codigo-seguranca>" +
		"   <token>%25$s</token>" +
		"</dados-portador>";
	
	private static final String parte_3 = "" +
		"<dados-pedido>" + 
			"<numero>%5$s</numero>" + 
			"<valor>%6$s</valor>" + 
			"<moeda>%7$03d</moeda>" + 
			"<data-hora>%8$tY-%8$tm-%8$tdT%8$tH:%8$tM:%8$tS</data-hora>" +
			"<descricao>%15$s</descricao>" +			
			"<idioma>%16$s</idioma>" +
			"%17$s" +//Parametro para adicionar taxa de embarque
			"<soft-descriptor>%18$s</soft-descriptor>"+
		"</dados-pedido>" + 
		"<forma-pagamento>" + 
			"<bandeira>%9$s</bandeira>" +		
			"<produto>%10$s</produto>" + 
			"<parcelas>%11$s</parcelas>" + 
		"</forma-pagamento>" + 
		"<url-retorno>%12$s</url-retorno>" + 
		"<autorizar>%13$d</autorizar>" + 
		"<capturar>%14$s</capturar>" +
		"<gerar-token>%19$s</gerar-token>" +
		"%20$s" +
	"</requisicao-transacao>";	
	
	private static final String parte_1_celular = "" +
			"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" + 
			"<requisicao-nova-transacao-celular id=\"%1$s\" versao=\"%2$s\">" + 
				"<dados-ec>" + 
				"	<numero>%3$d</numero>" + 
				"	<chave>%4$s</chave>" + 
				"</dados-ec>" ;

	private static final String parte_2_celular = 			
			"<dados-portador>" +
			"	<ddd>%22$s</ddd>" +
			"	<numero>%23$s</numero>" +
			"</dados-portador>" ;
			
	
	private static final String parte_3_celular = 		
		"<dados-pedido>" + 
			"<numero>%5$s</numero>" + 
			"<valor>%6$s</valor>" + 
			"<moeda>%7$03d</moeda>" + 
			"<data-hora>%8$tY-%8$tm-%8$tdT%8$tH:%8$tM:%8$tS</data-hora>" +
			"<descricao>%15$s</descricao>" +			
			"<idioma>%16$s</idioma>" +
		"</dados-pedido>" + 
		"<forma-pagamento>" + 	
			"<produto>%10$s</produto>" + 
			"<parcelas>%11$s</parcelas>" + 
		"</forma-pagamento>" + 
		"<url-retorno>%12$s</url-retorno>" + 
		"<capturar>%14$s</capturar>" +
		"<campo-livre>%21$s</campo-livre>" +
		"<gerar-token>%19$s</gerar-token>" +
		"%20$s" +
	"</requisicao-nova-transacao-celular>";
	
	
	private Pedido pedido;
	
	public MensagemNovaTransacao(Loja loja, Pedido pedido) {
		super(loja);
		this.pedido = pedido;
	}

	String getTemplate() {
		if (pedido.temCartao()) {
			return parte_1 + parte_2 + parte_3;
		}
		else { 
			if(pedido.temCelular()){
				return parte_1_celular + parte_2_celular + parte_3_celular;
			}else{
				if(pedido.isCelularBuyPage()){
					return parte_1_celular + parte_3_celular;
				}else{
					return parte_1 + parte_3;
				}
			}
		}
	}

	Object[] getArgumentos() {
		ConfiguracaoTransacao configuracaoTransacao = pedido.getConfiguracaoTransacao();
		
		List<Object> argumentos = new ArrayList<Object>();
		
		argumentos.add(0, getId());
		argumentos.add(1, getVersao());
		argumentos.add(2, getLoja().getNumero());
		argumentos.add(3, getLoja().getChave());
		argumentos.add(4, pedido.getNumero());
		argumentos.add(5, pedido.getValor());
		argumentos.add(6, 986);
		argumentos.add(7, pedido.getData());
		argumentos.add(8, pedido.getFormaPagamento().getBandeira().getNome());
		argumentos.add(9, pedido.getFormaPagamento().getModalidade().getCodigo());
		argumentos.add(10, pedido.getFormaPagamento().getParcelas());
		argumentos.add(11, configuracaoTransacao.getUrlRetorno());
		if(configuracaoTransacao.getIndicadorAutorizacao()!=null){
			argumentos.add(12, configuracaoTransacao.getIndicadorAutorizacao().getCodigo());
		}else{
			argumentos.add(12, "");
		}
		argumentos.add(13, configuracaoTransacao.isCapturarAutomaticamente());
		argumentos.add(14, pedido.getDescricao());
		argumentos.add(15, configuracaoTransacao.getIdioma());
		argumentos.add(16, configuracaoTransacao.isComTaxaEmbarque() ? "<taxa-embarque>" + String.valueOf(pedido.getTaxaEmbarque())+ "</taxa-embarque>" : " " );
		argumentos.add(17, pedido.getSoftDescritor());
		argumentos.add(18, configuracaoTransacao.isGerarToken());
		
		if (pedido.getCartao() != null) {
			argumentos.add(19, getAvs());
			Cartao cartao = pedido.getCartao();
			
			argumentos.add(20, cartao.getNumero());
			argumentos.add(21, cartao.getValidade());
			argumentos.add(22, cartao.getIndicadorCodigoSeguranca().getCodigo());
			argumentos.add(23, cartao.getCodigoSeguranca());
			argumentos.add(24, cartao.getToken());
		} else {
			if(pedido.getCelular()!=null){
				argumentos.add(19, getAvs());
				argumentos.add(20, pedido.getCampoLivre() == null? "":pedido.getCampoLivre());
				Celular celular = pedido.getCelular();
				
				argumentos.add(21, celular.getDdd());
				argumentos.add(22, celular.getNumero());
				
			}else{
				argumentos.add(19, "");
				argumentos.add(20, "");
			}
			
		}
		
			
		return argumentos.toArray();
	}

	private String getAvs() {

		ConfiguracaoTransacao configuracaoTransacao = pedido.getConfiguracaoTransacao();
		String codigoAutorizacao;
		if(configuracaoTransacao.getIndicadorAutorizacao()==null){
			//se for vazio é transação com celular 
			//só autoriza se for autenticada
			codigoAutorizacao = "1";
		}else{
			codigoAutorizacao = String.valueOf(configuracaoTransacao.getIndicadorAutorizacao().getCodigo());
		}
		
		if(  pedido.getAvs() != null 
				&& codigoAutorizacao.equals("3")){
			return "<avs>" +
						"<![CDATA[" +
							"<dados-avs>" +
								"<cpf>" + pedido.getAvs().getCpf()+ "</cpf>" +
								"<endereco>"+ pedido.getAvs().getEndereco() +"</endereco>" +
								"<complemento>"+ pedido.getAvs().getComplemento() +"</complemento>" +
								"<numero>"+ pedido.getAvs().getNumero() + "</numero>" +
								"<bairro>"+ pedido.getAvs().getBairro()+"</bairro>" +
								"<cep>"+ pedido.getAvs().getCep() +"</cep>" +
							"</dados-avs>]]>" +
						"</avs>";
		} else {
			return "";
		}
	}

}
