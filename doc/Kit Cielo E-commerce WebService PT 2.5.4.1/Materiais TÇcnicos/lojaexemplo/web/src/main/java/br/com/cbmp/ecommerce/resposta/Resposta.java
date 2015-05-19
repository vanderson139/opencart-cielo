package br.com.cbmp.ecommerce.resposta;

public abstract class Resposta {
	
	private String id;
	
	private String conteudo;
	
	public String getConteudo() {
		return conteudo;
	}

	public void setConteudo(String xml) {
		this.conteudo = xml;
	}

	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}
}
