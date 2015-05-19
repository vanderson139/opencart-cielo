package br.com.cbmp.ecommerce.util;

import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;

import br.com.cbmp.ecommerce.agendamento.Agenda;

public class InitializationListener implements ServletContextListener {
	
	public void contextDestroyed(ServletContextEvent arg0) {
	}

	public void contextInitialized(ServletContextEvent arg0) {
		Agenda.ativar();
	}

}
