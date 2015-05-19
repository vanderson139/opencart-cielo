package br.com.cbmp.ecommerce.pedido;

public enum Bandeira {
	
	VISA("visa"),
	MASTERCARD("mastercard"),
	ELO("elo"),
	AMEX("amex"),
	DINERS("diners"),
	DISCOVER("discover"),
	AURA("aura"),
	JCB("jcb"),
	CELULAR("celular");
	
	private String nome;
	
	private Bandeira(String nome) {
		this.nome = nome;
	}
	
	public static Bandeira valueOf(int codigo) {
		switch (codigo) {
		case 1:
			return VISA;
		case 2:
			return MASTERCARD;
		case 3:
			return ELO;
		case 4:
			return AMEX;
		case 5:
			return DINERS;
		case 6:
			return DISCOVER;
		case 7:
			return AURA;
		case 8:
			return JCB;
		case 9:
			return CELULAR;
		default:
			throw new IllegalArgumentException("Código '" + codigo + "' de bandeira não suportado.");
		}
	}

	public String getNome() {
		return nome;
	}

	@Override
	public String toString() {
		return nome;
	}
	
	
	

}
