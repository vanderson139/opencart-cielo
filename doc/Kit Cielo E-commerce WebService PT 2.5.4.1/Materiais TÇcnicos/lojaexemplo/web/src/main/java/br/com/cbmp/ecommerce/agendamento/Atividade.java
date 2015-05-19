package br.com.cbmp.ecommerce.agendamento;

import java.math.BigDecimal;

import org.apache.log4j.Logger;

import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.Cartao;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;
import br.com.cbmp.ecommerce.pedido.Pedido;
import br.com.cbmp.ecommerce.pedido.Produto;
import br.com.cbmp.ecommerce.util.Produtos;

class Atividade implements Runnable {
	
	private static final Logger logger = Logger.getLogger(Atividade.class);
	
	public static void main(String[] args) {
		Atividade atividade = new Atividade();
		atividade.run();
	}

	public void run() {
		try {
			logger.debug("### Executando ###");
			
			FormaPagamento formaPagamento = new FormaPagamento(Modalidade.CREDITO_A_VISTA, 1, Bandeira.VISA);
			Produto produto = Produtos.todos().iterator().next();
			
			produto = new Produto(11, "produto caro", "100,");
			
			Pedido pedido = new Pedido(produto, formaPagamento);
			
			Loja loja = Loja.leituraCartaoLoja();
			pedido.setLoja(loja);
			
			Cartao cartao = new Cartao("XXXXXXXXXXXXXXXX", "YYYYMM", "123", "xzxzxzxzxz");
			pedido.setCartao(cartao);
			
			long init = System.currentTimeMillis();
			
			pedido.criarTransacao();
			//pedido.finalizarComAutorizacaoDireta();
			
			long tempoDecorrido = System.currentTimeMillis() - init;
			
			if (tempoDecorrido > 10000) {
				logger.warn("Execu��o com mais de 10s: " + tempoDecorrido + " msecs [tid='" + pedido.getTransacao().getTid() + "']");
			}
			
			logger.debug(pedido.getTransacao().getConteudo());
		}
		catch (Exception e) {
			logger.error(e, e);
		}
	}
}