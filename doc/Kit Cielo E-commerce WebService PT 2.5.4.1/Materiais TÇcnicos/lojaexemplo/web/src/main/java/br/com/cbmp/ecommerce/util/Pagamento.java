package br.com.cbmp.ecommerce.util;

import org.apache.commons.lang.StringUtils;

import br.com.cbmp.ecommerce.pedido.Bandeira;
import br.com.cbmp.ecommerce.pedido.FormaPagamento;
import br.com.cbmp.ecommerce.pedido.Modalidade;

public class Pagamento {

	public static FormaPagamento inferirFormaPagamento(String frPagamento, String tipoParcelamento,
			String codigoBandeira) {
		
		Modalidade modalidade;
		short parcelas;

		boolean isParcelado = StringUtils.isNumeric(frPagamento);
		if (isParcelado) {
			modalidade = Modalidade.valueOf(tipoParcelamento.charAt(0));
			parcelas = Short.parseShort(frPagamento);
		} else {
			char frmPagamento = frPagamento.charAt(0);

			modalidade = frmPagamento == 'D' ? Modalidade.DEBITO : Modalidade.CREDITO_A_VISTA;
			parcelas = 1;
		}

		Bandeira bandeira = Bandeira.valueOf(Integer.parseInt(codigoBandeira));

		return new FormaPagamento(modalidade, parcelas, bandeira);
	}

}
