package br.com.cbmp.ecommerce.pedido;

public enum StatusTransacao {

	CRIADA("Criada"),	
	EM_ANDAMENTO("Em andamento"),
	
	AUTENTICADA("Autenticada"),
	NAO_AUTENTICADA("Não autenticada"),
	
	AUTORIZADA("Autorizada"),
	NAO_AUTORIZADA("Não autorizada"),
	
	NAO_CAPTURADA("Não Capturada"),
	CAPTURADA("Capturada"),
	
	CANCELADA("Cancelada"),
	EM_AUTENTICACAO("Em autenticação");	
	
	private String descricao;
	
	private StatusTransacao(String descricao) {
		this.descricao = descricao;
	}
	
	public static StatusTransacao valueOf(short codigo) {
		switch(codigo) {
			case 0: return CRIADA;
			case 1: return EM_ANDAMENTO; 
			case 2: return AUTENTICADA;
			case 3: return NAO_AUTENTICADA;
			case 4: return AUTORIZADA;
			case 5: return NAO_AUTORIZADA;
			case 6: return CAPTURADA;
			case 8: return NAO_CAPTURADA;
			case 9: return CANCELADA;
			case 10: return EM_AUTENTICACAO;
			default:
				throw new IllegalArgumentException("Status '" + codigo + "' não catalogado");
		}
	}
	
	public boolean isAutorizada() {
		return this == AUTORIZADA;
	}
	
	public boolean isNaoAutorizada() {
		return this == NAO_AUTORIZADA;
	}
	
	public boolean isAutenticada() {
		return this == AUTENTICADA;
	}
	
	public boolean isNaoAutenticada() {
		return this == NAO_AUTENTICADA;
	}
	
	public boolean isCapturada() {
		return this == CAPTURADA;
	}
	
	public boolean isCancelada() {
		return this == CANCELADA;
	}
	
	@Override
	public String toString() {
		return descricao;
	}

}
