package br.com.cbmp.ecommerce;

import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;

import junit.framework.TestCase;

import org.apache.log4j.Logger;

import br.com.cbmp.ecommerce.contexto.ConfiguracaoTransacao;
import br.com.cbmp.ecommerce.contexto.Destino;
import br.com.cbmp.ecommerce.contexto.Loja;

public class BaseTestCase extends TestCase {
	
	protected Loja loja = Loja.leituraCartaoCielo();
	
	protected Destino destino = new Destino();
	
	protected ConfiguracaoTransacao configuracaoTransacao = new ConfiguracaoTransacao();
	
	protected void escreverParaArquivo(String xml, String nomeArquivo) throws IOException, FileNotFoundException {
		String nomeArquivoCompleto = System.getProperty("user.dir") + "/src/test/resources/" + nomeArquivo;
		new FileOutputStream(nomeArquivoCompleto).write(xml.getBytes());
	}	
	
	protected Logger getLogger() {
		return Logger.getLogger(getClass());
	}

}
