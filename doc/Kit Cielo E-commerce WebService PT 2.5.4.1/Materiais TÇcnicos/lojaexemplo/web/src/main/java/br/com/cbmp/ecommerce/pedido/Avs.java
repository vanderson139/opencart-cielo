package br.com.cbmp.ecommerce.pedido;

import java.io.Serializable;

public class Avs implements Serializable {

	private static final long serialVersionUID = -59012002059902339L;

	private String idioma;
	private String bairro;
	private String cep;
	private String complemento;
	private String cpf;
	private String descricaoRetorno;
	private String endereco;
	private String numero;
	private char   retornoAvs;
	
	public String getIdioma() {
		return idioma;
	}
	public void setIdioma(String idioma) {
		this.idioma = idioma;
	}
	
	public String getBairro() {
		return bairro;
	}
	public void setBairro(String bairro) {
		this.bairro = bairro;
	}
	
	public String getCep() {
		return cep;
	}
	public void setCep(String cep) {
		this.cep = cep;
	}
	
	public String getComplemento() {
		return complemento;
	}
	public void setComplemento(String complemento) {
		this.complemento = complemento;
	}
	
	public String getCpf() {
		return cpf;
	}
	public void setCpf(String cpf) {
		this.cpf = cpf;
	}
	
	public String getDescricaoRetorno() {
		return descricaoRetorno;
	}
	public void setDescricaoRetorno(String descricaoRetorno) {
		this.descricaoRetorno = descricaoRetorno;
	}
	
	public String getEndereco() {
		return endereco;
	}
	public void setEndereco(String endereco) {
		this.endereco = endereco;
	}
	
	public String getNumero() {
		return numero;
	}
	public void setNumero(String numero) {
		this.numero = numero;
	}
	
	public char getRetornoAvs() {
		return retornoAvs;
	}
	public void setRetornoAvs(char retornoAvs) {
		this.retornoAvs = retornoAvs;
	}
}
