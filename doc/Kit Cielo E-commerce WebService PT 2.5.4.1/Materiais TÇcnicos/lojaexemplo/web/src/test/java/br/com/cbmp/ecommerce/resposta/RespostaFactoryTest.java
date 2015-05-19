package br.com.cbmp.ecommerce.resposta;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.resposta.Transacao.Processamento;
import br.com.cbmp.ecommerce.resposta.Transacao.Token;

public class RespostaFactoryTest extends BaseTestCase {
	
//	public void testMensagemErro99() {
//		final String retorno = "" +
//			"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>" +
//			"<erro id='123'>" +
//				"<codigo>099</codigo>" +
//				"<mensagem>[-3f3b3124:1261b4f933b:-7fda]Erro inesperado</mensagem>" +
//			"</erro>";
//		
//		
//		Resposta resposta = RespostaFactory.getInstance().criar(retorno);
//		
//		Erro erro = (Erro) resposta;
//		
//		assertEquals(99, erro.getCodigo());
//		assertEquals("[-3f3b3124:1261b4f933b:-7fda]Erro inesperado", erro.getMensagem());
//		
//		System.out.println(resposta.getId());
//	}

	public void testMensagemRetornoToken(){
		final String retornoXml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>"+
								"<retorno-token id=\"0aaf2d16-e88a-4908-8081-26ae1791fbe7\" versao=\"1.2.0\" xmlns=\"http://ecommerce.cbmp.com.br\">"+
									"<token>"+
										"<dados-token>"+
											"<codigo-token>34cd21ff7edfff441f11a41141c3065b</codigo-token>"+
											"<status>1</status>"+
											"<numero-cartao-truncado>455187******0183</numero-cartao-truncado>"+
										"</dados-token>"+
									"</token>"+
								"</retorno-token>";
		
		Resposta resposta = RespostaFactory.getInstance().criar(retornoXml);

		Transacao retorno = (Transacao) resposta;
		
		assertEquals("34cd21ff7edfff441f11a41141c3065b", retorno.getToken().getDadosToken().getCodigoToken());
		assertEquals('1', retorno.getToken().getDadosToken().getStatus());
		assertEquals("455187******0183", retorno.getToken().getDadosToken().getNumeroCartaoTruncado());
		
		System.out.println(resposta.getId());
	}
}
