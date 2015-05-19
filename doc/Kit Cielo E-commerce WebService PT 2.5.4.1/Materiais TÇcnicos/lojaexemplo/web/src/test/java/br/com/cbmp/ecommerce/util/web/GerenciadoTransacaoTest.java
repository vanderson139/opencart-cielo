package br.com.cbmp.ecommerce.util.web;

import java.util.HashMap;
import java.util.Map;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.requisicao.Mensagem;
import br.com.cbmp.ecommerce.requisicao.MensagemConsulta;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;

public class GerenciadoTransacaoTest extends BaseTestCase {
	
	public void testConsultar() throws FalhaComunicaoException {
		final Map<String, String> request = new HashMap<String, String>();
		request.put("acao", "CONSULTA");
		request.put("tid", "10069930690020251001");
		request.put("numeroLoja", "1006993069");
		request.put("valorOperacao", "0");
		
	
		GerenciadorTransacao gerenciadorTransacao = new GerenciadorTransacao() {
			
			String getParameter(String parameter) {
				return request.get(parameter);
			}
			
			String requisitar(Mensagem mensagem) throws FalhaComunicaoException {
				assertTrue(mensagem instanceof MensagemConsulta);
				assertEquals(Loja.leituraCartaoLoja(), mensagem.getLoja());
				return "consulta";
			}
			
		};
		
		String resultado = gerenciadorTransacao.executar();
		
		assertEquals("Resultado inesperado.", "consulta", resultado);
	}

}
