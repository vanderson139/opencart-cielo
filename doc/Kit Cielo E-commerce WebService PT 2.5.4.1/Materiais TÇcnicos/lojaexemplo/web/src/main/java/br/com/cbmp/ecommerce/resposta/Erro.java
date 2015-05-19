package br.com.cbmp.ecommerce.resposta;

import org.apache.commons.lang.builder.ToStringBuilder;
import org.apache.commons.lang.builder.ToStringStyle;

public class Erro extends Resposta {
	
	private String codigo;
	
	private String mensagem;
	
	public int getCodigo() {
		return Integer.parseInt(codigo);
	}

	public String getMensagem() {
		return mensagem;
	}

	@Override
	public String toString() {
		return ToStringBuilder.reflectionToString(this, ToStringStyle.MULTI_LINE_STYLE);
	}

}
