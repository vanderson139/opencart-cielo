package br.com.cbmp.ecommerce.util;

import javax.servlet.http.HttpSessionEvent;
import javax.servlet.http.HttpSessionListener;

public class LotesSessionListener implements HttpSessionListener{

	public void sessionCreated(HttpSessionEvent event) {
		event.getSession().setAttribute("lotes", new Lotes());
	}

	public void sessionDestroyed(HttpSessionEvent event) {
	}

}
