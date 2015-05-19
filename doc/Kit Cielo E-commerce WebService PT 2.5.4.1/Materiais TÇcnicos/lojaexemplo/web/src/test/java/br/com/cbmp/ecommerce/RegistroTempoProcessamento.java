package br.com.cbmp.ecommerce;

import org.apache.commons.lang.time.StopWatch;
import org.apache.log4j.Logger;

public class RegistroTempoProcessamento {
	
	private static final Logger logger = Logger.getLogger(RegistroTempoProcessamento.class);
	
	public static void registrar(StopWatch stopWatch) {
		logger.warn(stopWatch.getTime());
	}

}
