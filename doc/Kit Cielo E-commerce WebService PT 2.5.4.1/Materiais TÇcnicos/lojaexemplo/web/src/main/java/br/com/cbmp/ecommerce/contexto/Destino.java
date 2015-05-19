package br.com.cbmp.ecommerce.contexto;

import org.apache.commons.lang.builder.ToStringBuilder;
import org.apache.commons.lang.builder.ToStringStyle;

public class Destino {
	
	private String url;
	
	private String urlDownloadLote;
	
	public Destino(String url) {
		this.url = url;
	}
	
	public Destino() {
		this.url = DestinoUrl.ECOM.getUrl();
	}
	
	public Destino(DestinoUrl destino){
		this.url = destino.getUrl();
	}

	public String getUrl() {
		return url;
	}
	
	public String getUrlDownloadLote(){
		return this.urlDownloadLote;
	}
	
	@Override
	public String toString() {
		return ToStringBuilder.reflectionToString(this, ToStringStyle.MULTI_LINE_STYLE);
	}

}
