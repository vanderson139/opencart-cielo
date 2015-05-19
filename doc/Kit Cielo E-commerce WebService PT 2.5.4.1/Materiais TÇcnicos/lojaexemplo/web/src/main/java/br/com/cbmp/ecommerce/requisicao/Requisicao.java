package br.com.cbmp.ecommerce.requisicao;

import java.io.DataOutputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.UUID;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.httpclient.Header;
import org.apache.commons.httpclient.HeaderElement;
import org.apache.commons.httpclient.HttpClient;
import org.apache.commons.httpclient.HttpException;
import org.apache.commons.httpclient.MultiThreadedHttpConnectionManager;
import org.apache.commons.httpclient.NameValuePair;
import org.apache.commons.httpclient.methods.PostMethod;
import org.apache.commons.httpclient.Credentials;
import org.apache.commons.httpclient.auth.AuthScope;
import org.apache.commons.httpclient.UsernamePasswordCredentials;
import org.apache.commons.lang.time.StopWatch;
import org.apache.log4j.Logger;

import br.com.cbmp.ecommerce.contexto.Destino;
import br.com.cbmp.ecommerce.pedido.Lote;
import br.com.cbmp.ecommerce.resposta.Erro;
import br.com.cbmp.ecommerce.resposta.FalhaComunicaoException;
import br.com.cbmp.ecommerce.resposta.RequisicaoInvalidaException;
import br.com.cbmp.ecommerce.resposta.Resposta;
import br.com.cbmp.ecommerce.resposta.RespostaFactory;
import br.com.cbmp.ecommerce.resposta.Transacao;
import br.com.cbmp.ecommerce.tempoprocessamento.RegistroTempoProcessamento;


public class Requisicao {
	
	private static final int _1_SEGUNDO = 1000;
	
	private static final int _CONNECTION_TIMEOUT = 10 * _1_SEGUNDO;

	private static final int _READ_TIMEOUT = 40 * _1_SEGUNDO;

	private static final Logger logger = Logger.getLogger(Requisicao.class);
	
	private Mensagem mensagem;
	
	private static HttpClient httpClient;
	
	static {
		httpClient = new HttpClient();
		httpClient.setHttpConnectionManager(new MultiThreadedHttpConnectionManager());
		httpClient.getHttpConnectionManager().getParams().setConnectionTimeout(_CONNECTION_TIMEOUT);
		httpClient.getHttpConnectionManager().getParams().setSoTimeout(_READ_TIMEOUT);
		httpClient.getHttpConnectionManager().closeIdleConnections(_1_SEGUNDO);

	}

	public Requisicao(Mensagem mensagem) {
		this.mensagem = mensagem;
	}

	public Transacao enviarPara(Destino destino) throws FalhaComunicaoException {
		String mensagemXml = mensagem.toXml();
		
		PostMethod httpMethod = new PostMethod(destino.getUrl());
		httpMethod.addParameter("mensagem", mensagemXml);
		
		if (logger.isDebugEnabled()) {
			logger.debug("Destino: '" + destino.getUrl() + "'\nMensagem: \n" + mensagemXml);
		}
		
		try {
			StopWatch stopWatch = new StopWatch();
			stopWatch.start();
			
			httpClient.executeMethod(httpMethod);
			
			String respostaXml = httpMethod.getResponseBodyAsString();
			
			stopWatch.stop();
			
			RegistroTempoProcessamento.registrar(stopWatch);
			
			if (logger.isDebugEnabled()) {
				logger.debug("Retorno [em " + stopWatch + ", id='" + mensagem.getId() + "']: \n" + respostaXml);
			}
			
			
			Resposta resposta = RespostaFactory.getInstance().criar(respostaXml);
			
			if (resposta.getId() != null && ! mensagem.getId().equals(resposta.getId())) {
				throw new IllegalArgumentException("Resposta inválida: idRecebido='" + resposta.getId()
						+ "', idEnviado='" + mensagem.getId() + "'.");
			}
			
			if (resposta instanceof Erro) {
				Erro erro = (Erro) resposta;
				throw new RequisicaoInvalidaException(erro);
			}
			
			Transacao transacao = (Transacao) resposta;
			
			return transacao;
		} 
		catch (HttpException e) {
			logger.error(e, e);
			throw new FalhaComunicaoException(e.getMessage());
		} 
		catch (IOException e) {
			logger.error(e, e);
			throw new FalhaComunicaoException(e.getMessage());
		}
		finally {
			httpMethod.releaseConnection();
		}
	}

	public Lote enviarPara(Destino destino, HttpServletRequest request, Lote lote) throws FalhaComunicaoException {
		
		String mensagemXml = mensagem.toXml();
		
		PostMethod httpMethod = new PostMethod(destino.getUrl());
		httpMethod.addParameter("mensagem", mensagemXml);
		
		if (logger.isDebugEnabled()) {
			logger.debug("Destino: '" + destino.getUrl() + "'\nMensagem: \n" + mensagemXml);
		}
		
		try {
			StopWatch stopWatch = new StopWatch();
			stopWatch.start();
			
			httpClient.executeMethod(httpMethod);	
			  
            byte[] responseBody = httpMethod.getResponseBody();
			
			Header header = httpMethod.getResponseHeader("Content-Disposition");
		
			if(header != null){
			
				StringBuilder absolutePath = new StringBuilder();

				NameValuePair param = null;
				for (HeaderElement element : header.getElements()) {
					if(element.getName().equals("attachment")){
						param = element.getParameterByName("filename");
					}
				}
				
				System.out.println("Path de Context: " + request.getContextPath());
		
				String pathDown = request.getSession().getServletContext().getInitParameter("path_download");
				
				if(param != null){
					String dirDownload = pathDown + param.getValue();					
					absolutePath.append(dirDownload);
				} else {
					absolutePath.append(pathDown  + UUID.randomUUID().toString() + ".xml");
				}
	
				DataOutputStream os = new DataOutputStream(new FileOutputStream(absolutePath.toString()));
				try {
					os.write(responseBody);
					
					os.close();
					
					lote.setPath(getNamedFile(request, param.getValue()));
					System.out.println("URL do arquivo : " + getNamedFile(request, param.getValue()));
					lote.setName(param.getValue());
					lote.setXmlRetorno(httpMethod.getResponseBodyAsString());
				} finally {
					os.close();
					os.flush();
				}
			} else {
				String resposta = httpMethod.getResponseBodyAsString();
				lote.setXmlRetorno(resposta);
			}
			
			stopWatch.stop();
			
			RegistroTempoProcessamento.registrar(stopWatch);
			
			if (logger.isDebugEnabled()) {
				logger.debug("Retorno [em " + stopWatch + ", id='" + mensagem.getId() + "']: \n" + 
						lote.getXmlRetorno());
			}
			return lote;
		} 
		catch (HttpException e) {
			logger.error(e, e);
			throw new FalhaComunicaoException(e.getMessage());
		} 
		catch (IOException e) {
			logger.error(e, e);
			throw new FalhaComunicaoException(e.getMessage());
		}
		finally {
			httpMethod.releaseConnection();
		}
	}

	private String getNamedFile(HttpServletRequest request, String fileName){
		String localAddress = "";
		if("127.0.0.1".equals(request.getLocalAddr())){
			localAddress = "localhost";
		} else {
			localAddress = request.getLocalAddr();
		}
		
		int localPort = request.getLocalPort();
		String contextPath = request.getContextPath();
		
		String scheme = request.getScheme();
		StringBuilder builder = new StringBuilder();
		builder
			.append(scheme)
			.append("://")
			.append(localAddress)
			.append(":")
			.append(localPort)
			.append(contextPath)
			.append("/loteret/")
			.append(fileName);
		
		return builder.toString();
	}
}
