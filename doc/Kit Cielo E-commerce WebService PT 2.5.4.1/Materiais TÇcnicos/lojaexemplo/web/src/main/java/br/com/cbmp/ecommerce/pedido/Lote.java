package br.com.cbmp.ecommerce.pedido;

import javax.servlet.http.HttpServletRequest;

import br.com.cbmp.ecommerce.contexto.Loja;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;

public class Lote {

	private Loja loja;
	
	private long numeroLote;
	
	private String xmlRetorno;
	
	private String path;
	
	private String name;
	
	public Lote(Loja loja, long numeroLote) {
		this.loja = loja;
		this.numeroLote = numeroLote;
	}
	private TransacaoService getTransacaoService() {
		return new TransacaoService(loja);
	}
	
	public Lote efetuarDownload(HttpServletRequest request) throws FalhaComunicaoException{
		return getTransacaoService().downloadLote(request, this);
	}

	public Loja getLoja() {
		return this.loja;
	}

	public long getNumeroLote() {
		return this.numeroLote;
	}
	public void setXmlRetorno(String xmlRetorno) {
		this.xmlRetorno = xmlRetorno;
	}
	public String getXmlRetorno() {
		return xmlRetorno;
	}
	public void setPath(String path) {
		this.path = path;
	}
	public String getPath() {
		return path;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getName() {
		return name;
	}
}
