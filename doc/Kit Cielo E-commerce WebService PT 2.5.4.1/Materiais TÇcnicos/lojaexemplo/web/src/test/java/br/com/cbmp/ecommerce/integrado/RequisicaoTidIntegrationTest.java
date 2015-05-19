package br.com.cbmp.ecommerce.integrado;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

import org.apache.log4j.Logger;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.contexto.Destino;
import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;
import br.com.cbmp.ecommerce.requisicao.Mensagem;
import br.com.cbmp.ecommerce.requisicao.MensagemTid;
import br.com.cbmp.ecommerce.requisicao.Requisicao;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;
import br.com.cbmp.ecommerce.resposta.Transacao;

public class RequisicaoTidIntegrationTest extends BaseTestCase {

	private static final Logger logger = Logger.getLogger(RequisicaoTidIntegrationTest.class);

	private static int requisicoesParalelas = 2;

	private static int numeroCiclos = 1000;
	
	private Destino dev = new Destino("http://192.168.40.26:7311/webservice/ecommwsec.do");
	
	private Destino dev2 = new Destino("http://192.168.40.26:7312/webservice/ecommwsec.do");
	
	private Destino devHttpHandler = new Destino("http://192.168.40.27:7001/servicos/ecommwsec.do");
	
	private Destino homHttpHandler = new Destino("http://10.80.50.121:7801/http_handler/eCommerce.do");
	
	private Destino hom = new Destino("http://10.80.50.121:7312/webservice/ecommwsec.do");
	
	private Destino homApache = new Destino("http://10.80.51.154:8780/servicos/ecommwsec.do");
	
	public static void main(String[] args) {

		for (int ciclo=0; ciclo<numeroCiclos; ciclo++) {
		
			Thread [] threads = new Thread[requisicoesParalelas]; 
	
			for (int i=0; i<requisicoesParalelas; i++) {
				threads[i] = new Thread(new Runnable(){
	
					public void run() {
						try {
							RequisicaoTidIntegrationTest test = new RequisicaoTidIntegrationTest();
							test.testObterTid();
						}
						catch (Exception e) {
							logger.error(e);
						}
					}});
			}
			
			for (int i=0; i<requisicoesParalelas; i++) {
				threads[i].start();
			}
			
			sleep();
		}
		
		askForName();
	}
	
	private static void sleep() {
		try {
			Thread.sleep(70);
		} 
		catch (InterruptedException e) {
			logger.error(e);
		}
		
	}

	public void testObterTid() throws FalhaComunicaoException {
		Loja loja = new Loja(1001734898L, "e84827130b9837473681c2787007da5914d6359947015a5cdb2b8843db0fa832");
		Transacao transacao;
		FormaPagamento formaPagamento = new FormaPagamento(Modalidade.CREDITO_A_VISTA, 4, Bandeira.VISA);
		Mensagem mensagem = new MensagemTid(loja, formaPagamento);
		Requisicao requisicao = new Requisicao(mensagem);
		
		int b = new Long(System.currentTimeMillis()).intValue() % 2;
		
		if (b != 0) {
			transacao = requisicao.enviarPara(homApache);
		}
		else {
			transacao = requisicao.enviarPara(homApache);
		}
		
		getLogger().info(transacao.getTid());		
	}
	
	private static void askForName() {
		//  prompt the user to enter their name
		  logger.debug("Enter your name: ");

		  //  open up standard input
		  BufferedReader br = new BufferedReader(new InputStreamReader(System.in));

		  String userName = null;

		  //  read the username from the command-line; need to use try/catch with the
		  //  readLine() method
		  try {
		     userName = br.readLine();
		  } catch (IOException ioe) {
		     logger.debug("IO error trying to read your name!");
		     System.exit(1);
		  }

		  logger.debug("Thanks for the name, " + userName);
	}
}
