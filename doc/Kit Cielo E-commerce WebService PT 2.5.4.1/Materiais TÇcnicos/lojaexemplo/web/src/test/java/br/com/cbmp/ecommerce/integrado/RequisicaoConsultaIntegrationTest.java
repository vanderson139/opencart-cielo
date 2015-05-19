package br.com.cbmp.ecommerce.integrado;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.contexto.Destino;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.requisicao.Mensagem;
import br.com.cbmp.ecommerce.requisicao.MensagemConsulta;
import br.com.cbmp.ecommerce.requisicao.Requisicao;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;
import br.com.cbmp.ecommerce.resposta.Transacao;

public class RequisicaoConsultaIntegrationTest extends BaseTestCase {

	Destino producao = new Destino("http://192.168.40.26:7311/webservice/ecommwsec.do");
	
	public void testConsulta() throws FalhaComunicaoException {
		Loja loja = new Loja(1001734898L, "e84827130b9837473681c2787007da5914d6359947015a5cdb2b8843db0fa832");
		Transacao transacao = new Transacao("10176934870064731001","123");
		Mensagem mensagem = new MensagemConsulta(loja, transacao);
		Requisicao requisicao = new Requisicao(mensagem);
		transacao = requisicao.enviarPara(producao);
		getLogger().info(transacao);		
	}
	
}
