package br.com.cbmp.ecommerce.resposta;

import javax.servlet.http.HttpServletRequest;

import br.com.cbmp.ecommerce.resposta.Transacao.Autorizacao;
import br.com.cbmp.ecommerce.resposta.Transacao.Cancelamento;
import br.com.cbmp.ecommerce.resposta.Transacao.Captura;
import br.com.cbmp.ecommerce.resposta.Transacao.DadosPedido;
import br.com.cbmp.ecommerce.resposta.Transacao.DadosPortador;
import br.com.cbmp.ecommerce.resposta.Transacao.DadosPortadorCelular;
import br.com.cbmp.ecommerce.resposta.Transacao.DadosToken;
import br.com.cbmp.ecommerce.resposta.Transacao.Processamento;
import br.com.cbmp.ecommerce.resposta.Transacao.Token;
import br.com.cbmp.ecommerce.pedido.Pedido;
import br.com.cbmp.ecommerce.pedido.Celular;
import br.com.cbmp.ecommerce.util.web.WebUtils;

import com.thoughtworks.xstream.XStream;
import com.thoughtworks.xstream.converters.collections.CollectionConverter;
import com.thoughtworks.xstream.mapper.ClassAliasingMapper;

public class RespostaFactory {
	
	private static final RespostaFactory INSTANCE = new RespostaFactory();
	
	private XStream xStream;
	
	private HttpServletRequest request;
	
	private RespostaFactory() {
		
		
		xStream = new XStream();
		xStream.alias("erro", Erro.class);
		xStream.alias("transacao", Transacao.class);
		xStream.alias("retorno-tid", Transacao.class);
		
		xStream.alias("retorno-token", Transacao.class);
		xStream.alias("token", Token.class);
		xStream.alias("dados-token", DadosToken.class);
		
		xStream.aliasAttribute(Resposta.class, "id", "id");
		
		xStream.aliasAttribute(Transacao.class, "dadosPedido", "dados-pedido");
		xStream.aliasAttribute(DadosPedido.class, "dataHora", "data-hora");
		xStream.aliasAttribute(DadosPedido.class, "taxaEmbarque", "taxa-embarque");
		xStream.aliasAttribute(Processamento.class, "dataHora", "data-hora");
		xStream.aliasAttribute(Autorizacao.class, "codigoAvsCep", "codigo-avs-cep");
		xStream.aliasAttribute(Autorizacao.class, "mensagemAvsCep", "mensagem-avs-cep");
		xStream.aliasAttribute(Autorizacao.class, "codigoAvsEnd", "codigo-avs-end");
		xStream.aliasAttribute(Autorizacao.class, "mensagemAvsEnd", "mensagem-avs-end");
		
		xStream.aliasAttribute(Transacao.class, "formaPagamento", "forma-pagamento");
		xStream.aliasAttribute(Captura.class, "valorTaxaEmbarque", "valorTaxaEmbarque");

		xStream.aliasAttribute(Transacao.class, "urlAutenticacao", "url-autenticacao");
		xStream.aliasAttribute(Transacao.class, "token","token");
		

			xStream.aliasAttribute(Token.class,"dadosToken", "dados-token");
			xStream.aliasAttribute(DadosToken.class,  "codigoToken","codigo-token");
			xStream.aliasAttribute(DadosToken.class,  "numeroCartaoTruncado","numero-cartao-truncado");
			xStream.aliasAttribute(DadosToken.class,  "status","status");

			xStream.aliasAttribute(Transacao.class, "dadosPortador", "dados-portador");
			xStream.aliasAttribute(DadosPortador.class, "numero", "numero"); 
			xStream.aliasAttribute(DadosPortador.class, "validade", "validade");
			xStream.aliasAttribute(DadosPortador.class, "codigoSeguranca", "codigo-seguranca");
			xStream.aliasAttribute(DadosPortador.class, "token", "token");
		
		ClassAliasingMapper mapperTokenErro = new ClassAliasingMapper(xStream.getMapper());
		mapperTokenErro.addClassAlias("erro", Erro.class);
		xStream.registerLocalConverter(Token.class, "token", new CollectionConverter(mapperTokenErro));
		
		ClassAliasingMapper mapperCancelamento = new ClassAliasingMapper(xStream.getMapper());
		mapperCancelamento.addClassAlias("cancelamento", Cancelamento.class);
		xStream.registerLocalConverter(Transacao.class, "cancelamentos", new CollectionConverter(mapperCancelamento));
			
	}
	
	public static RespostaFactory getInstance() {
		return INSTANCE;
	}
	
	public Resposta criar(String xml) {
		Object obj = xStream.fromXML(xml);

		Resposta resposta = (Resposta) obj;
		resposta.setConteudo(xml);
		
		return resposta;
	}

}
