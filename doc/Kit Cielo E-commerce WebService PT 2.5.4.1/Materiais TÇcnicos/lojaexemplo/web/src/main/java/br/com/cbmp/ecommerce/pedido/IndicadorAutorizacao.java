package br.com.cbmp.ecommerce.pedido;

public enum IndicadorAutorizacao {
	
	AUTORIZAR_RECORRENCIA(4,"Recorrencia"),
	AUTORIZAR_DIRETO(3, "Autorizar Direto"),
	AUTORIZAR_AUTENTICADA_NAOAUTENTICADA(2, "Autorizar transação autenticada e não-autenticada"),
	NAO_AUTORIZAR(0, "Somente autenticar a transação"),
	AUTORIZAR_AUTENTICADA(1, "Autorizar transação somente se autenticada"),
	;

	private short codigo;
	
	private String descricao;
	
	private IndicadorAutorizacao(int codigo, String descricao) {
		this.codigo = (short) codigo;
		this.descricao = descricao;
	}

	public short getCodigo() {
		return codigo;
	}

	public String getDescricao() {
		return descricao;
	}
	
	public static IndicadorAutorizacao valueOf(int codigo) {
		switch (codigo) {
			case 0:
				return NAO_AUTORIZAR;
			case 1:
				return AUTORIZAR_AUTENTICADA;
			case 2:
				return AUTORIZAR_AUTENTICADA_NAOAUTENTICADA;
			case 3:
				return AUTORIZAR_DIRETO;
			case 4:
				return AUTORIZAR_RECORRENCIA;
			default:
				throw new IllegalArgumentException("Valor '" + codigo + "' não reconhecido como uma flag válida.");
		}
	}

}
