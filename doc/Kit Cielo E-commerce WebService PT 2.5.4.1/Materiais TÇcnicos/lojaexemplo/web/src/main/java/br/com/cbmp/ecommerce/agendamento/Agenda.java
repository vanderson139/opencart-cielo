package br.com.cbmp.ecommerce.agendamento;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.ScheduledFuture;
import java.util.concurrent.TimeUnit;

import org.apache.log4j.Logger;

public class Agenda {
	
	private static final Logger logger = Logger.getLogger(Agenda.class);

	public static void ativar() {
		// Get the scheduler
		ScheduledExecutorService scheduler = Executors.newSingleThreadScheduledExecutor();

		// Get a handle, starting now, with a 10 second delay
		final ScheduledFuture<?> timeHandle = scheduler.scheduleAtFixedRate(new Atividade(),
				getDelay("28/01/2010 10:00"), 60 * 1000, TimeUnit.MILLISECONDS);

		// Schedule the event, and run for 2 hour (2 * 60 * 60 seconds)
		scheduler.schedule(new Runnable() {
			public void run() {
				timeHandle.cancel(false);
			}
		}, 3 * 24 * 60 * 60, TimeUnit.SECONDS);
		
		logger.info("Iniciado! Delay=" + timeHandle.getDelay(TimeUnit.SECONDS));
	}

	private static long getDelay(String string) {
		SimpleDateFormat simpleDateFormat = new SimpleDateFormat("dd/MM/yyyy HH:mm");
		simpleDateFormat.setLenient(false);
		
		try {
			Date data = simpleDateFormat.parse(string);
			return data.getTime() - System.currentTimeMillis();
		} 
		catch (ParseException e) {
			throw new RuntimeException(e);
		}
	}
}