package br.com.cbmp.ecommerce.pedido;

import org.apache.commons.lang.builder.ToStringBuilder;
import org.apache.commons.lang.builder.ToStringStyle;

public class FormaPagamento {
	
	private Bandeira bandeira;
	
	private Modalidade modalidade;
	
	private int parcelas;
	
	public FormaPagamento(Modalidade modalidade, int parcelas, Bandeira bandeira) {
		super();
		this.bandeira = bandeira;
		this.modalidade = modalidade;
		this.parcelas = parcelas;
	}
	
	public FormaPagamento(Modalidade modalidade, int parcelas){
		super();
		this.modalidade = modalidade;
		this.parcelas = parcelas;
	}

	public Modalidade getModalidade() {
		return modalidade;
	}

	public int getParcelas() {
		return parcelas;
	}
	
	@Override
	public String toString() {
		return ToStringBuilder.reflectionToString(this, ToStringStyle.MULTI_LINE_STYLE);
	}

	public Bandeira getBandeira() {
		return bandeira;
	}
	
}
