package br.com.cbmp.ecommerce.util;

import javax.servlet.http.HttpSessionEvent;
import javax.servlet.http.HttpSessionListener;

public class PedidosSessionListener implements HttpSessionListener {

	public void sessionCreated(HttpSessionEvent event) {
		event.getSession().setAttribute("pedidos", new Pedidos());
	}

	public void sessionDestroyed(HttpSessionEvent arg0) {
	}

}
