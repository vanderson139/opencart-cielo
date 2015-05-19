package br.com.cbmp.ecommerce.integrado;

import java.math.BigDecimal;

import br.com.cbmp.ecommerce.BaseTestCase;
import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.Cartao;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;
import br.com.cbmp.ecommerce.pedido.Pedido;
import br.com.cbmp.ecommerce.pedido.Produto;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;
import br.com.cbmp.ecommerce.util.Produtos;

public class PedidoAutorizacaoDiretaIntegrationTest extends BaseTestCase {
	
	public static void main(String[] args) throws FalhaComunicaoException {
		final PedidoAutorizacaoDiretaIntegrationTest teste = new PedidoAutorizacaoDiretaIntegrationTest();
		int nuThreads = 10;
		
		Thread [] threads = new Thread[nuThreads];
		
		for (int i = 0; i < nuThreads; i++) {
			threads[i] = new Thread(new Runnable() {

				public void run() {
					try {
						teste.testAutorizacaoDireta();
					} 
					catch (FalhaComunicaoException e) {
					}
				}});
		}
		
		for (int i = 0; i < nuThreads; i++) {
			
			try {
				Thread.sleep(100);
			} 
			catch (InterruptedException e) {
				e.printStackTrace();
			}
			System.out.println(i + " iniciado");
			
			threads[i].start();
		}
	}
	
	public void testAutorizacaoDireta() throws FalhaComunicaoException {
		FormaPagamento formaPagamento = new FormaPagamento(Modalidade.CREDITO_A_VISTA, 1, Bandeira.ELO);
		Produto produto = Produtos.todos().iterator().next();
		
		produto = new Produto(11, "produto caro", "10000");
		
		Pedido pedido = new Pedido(produto, formaPagamento);
		Cartao cartao = new Cartao("6362970000457013", "201501", "123", "123098312837h0982n09sdas09sd809sadasd809");
		pedido.setCartao(cartao);
		pedido.finalizarComAutorizacaoDireta();
		//pedido.criarTransacao();
		getLogger().info(pedido.getTransacao());		
	}

}
