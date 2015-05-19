package br.com.cbmp.ecommerce.requisicao;

import java.io.File;
import java.io.IOException;
import java.util.Iterator;
import java.util.List;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.fileupload.FileItem;
import org.apache.commons.fileupload.disk.DiskFileItemFactory;
import org.apache.commons.fileupload.servlet.ServletFileUpload;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.apache.log4j.Logger;

import br.com.cbmp.ecommerce.contexto.DestinoUrl;

import com.sun.org.apache.xerces.internal.impl.xpath.regex.ParseException;

public class RequisicaoLote {
	private static final Logger logger = Logger.getLogger(Requisicao.class);
	private DefaultHttpClient httpClient;
	private HttpPost post;
	private MultipartEntity entity;
	private int maxFileSize = 50 * (1024 * 1024);
	private int maxMemSize = 4 * 1024;
	private File file;
	private boolean isMultipart;
	private String filePath;
	
	public RequisicaoLote() {
		httpClient = new DefaultHttpClient();

		post = new HttpPost(DestinoUrl.UPLOAD_LOTE.getUrl());
		
		entity = new MultipartEntity( HttpMultipartMode.BROWSER_COMPATIBLE );
	}
	
	public String enviarPara(HttpServletRequest request) throws ParseException, ClientProtocolException, IOException {

		isMultipart = ServletFileUpload.isMultipartContent(request);
		
		// Pega o path temporário para o arquivo
		filePath = request.getSession().getServletContext().getInitParameter("path_upload");

		DiskFileItemFactory factory = new DiskFileItemFactory();
		// maximum size that will be stored in memory
		factory.setSizeThreshold(maxMemSize);
		// Location to save data that is larger than maxMemSize.
		
		factory.setRepository(new File(System.getProperty("java.io.tmpdir")));
		// Create a new file upload handler
		ServletFileUpload upload = new ServletFileUpload(factory);
		// maximum file size to be uploaded.
		upload.setSizeMax(maxFileSize);

		String response = "";

		try {
			// Parse the request to get file items.
			List fileItems = upload.parseRequest(request);
			// Process the uploaded file items
			Iterator i = fileItems.iterator();
			
			while (i.hasNext()) {
				FileItem fi = (FileItem) i.next();
				
				if (!fi.isFormField()) {
				
					if(fi.getName() == null || fi.getName().length() <= 0) {
						response = "";
					}
					else {
						if(!new File(filePath).exists()) {
							new File(filePath).mkdir();
						}
						 
						String nomeArquivo = "";
						for (int j = fi.getName().length() - 1 ; j >= 0; j--) {
							char c = fi.getName().charAt(j);
							nomeArquivo = c + nomeArquivo;
							if(c == '/' || c == '\\'){
								break;
							}
						}
						
						// Write the file
						file = new File(filePath + System.getProperty("file.separator") + nomeArquivo);
						FileBody fileBody = new FileBody(file, file.toURL().openConnection().getContentType());
						
						try {
							fi.write(file);
							
							// For File parameters
							entity.addPart("arqLoteUp", fileBody);
							post.setEntity( entity );
							
							// Here we go!
							System.out.println("Enviando");
							response = EntityUtils.toString( httpClient.execute( post ).getEntity(), "UTF-8" );
						}
						finally {
							fileBody.dispose();
							httpClient.getConnectionManager().shutdown();	
		
							fi.getOutputStream().close();
							fi.getOutputStream().flush();
							file.delete();
						}
					}
				}
			}
		} catch (Exception ex) {
			logger.error("Erro ao tentar subir o lote.", ex);
		}
		
		return response;
	}
}
