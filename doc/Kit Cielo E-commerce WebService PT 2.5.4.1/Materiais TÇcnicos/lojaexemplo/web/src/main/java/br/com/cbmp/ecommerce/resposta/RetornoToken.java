package br.com.cbmp.ecommerce.resposta;

import br.com.cbmp.ecommerce.resposta.Transacao.Token;

public class RetornoToken extends Resposta{
	
	private Token token;

	public void setToken(Token token) {
		this.token = token;
	}

	public Token getToken() {
		return token;
	}

}
