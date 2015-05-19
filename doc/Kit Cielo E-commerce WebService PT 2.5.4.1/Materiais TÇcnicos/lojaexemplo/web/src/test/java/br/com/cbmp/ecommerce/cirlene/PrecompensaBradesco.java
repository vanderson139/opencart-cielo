package br.com.cbmp.ecommerce.cirlene;

import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.apache.commons.io.IOUtils;

public class PrecompensaBradesco {

	
	private Map<String, String> hashCampanha = new HashMap<String, String>();
	
	public static void main(String[] args) throws IOException {
		PrecompensaBradesco precompensaBradesco = new PrecompensaBradesco();
		precompensaBradesco.tratar();
	}

	private void tratar() throws IOException {
		
		catalogarCampanhas();
		
		
		String filename = "D:\\temp\\ppo\\cirlene\\tbdwt_operacao_vmn.csv";
		InputStream inputStream = new FileInputStream(new File(filename));
		BufferedReader reader = new BufferedReader(new InputStreamReader(inputStream));
		
		BufferedOutputStream writer = new BufferedOutputStream(new FileOutputStream(new File("D:\\temp\\ppo\\cirlene\\tbdwt_operacao_vmn_out.csv")));
		
		List lines = IOUtils.readLines(reader);
		Iterator iterator = lines.iterator();
		
		iterator.next();
		
		int z = 0;
		while (iterator.hasNext()) {
			String linha = (String) iterator.next();
			
			String[] split = linha.split(";");
			
			for (int i=0; i<split.length; i++) {
				
				writer.write(split[i].getBytes("utf-8"));
				
				if (isIdCampanha(i)) {
					writer.write(";".getBytes("utf-8"));
					String nomeCampanha = getNomeCampanha(split[i]);
					writer.write(nomeCampanha.getBytes("utf-8"));
				}
				
				if (i != split.length) {
					writer.write(";".getBytes("utf-8"));
				}
			}
			
			writer.write("\n".getBytes());
			
			System.out.println(z++);
		}
		
		writer.close();
		
	}

	private void catalogarCampanhas() throws IOException {
		String filename = "D:\\temp\\ppo\\cirlene\\campanhas.csv";
		InputStream inputStream = new FileInputStream(new File(filename));
		BufferedReader reader = new BufferedReader(new InputStreamReader(inputStream));
		
		List lines = IOUtils.readLines(reader);
		
		Iterator iterator = lines.iterator();
		
		while (iterator.hasNext()) {
			String [] linha = ((String) iterator.next()).split(";");
			
			hashCampanha.put(linha[0], linha[1]);
		}
		
		System.out.println(lines.size() + " campanhas catalogadas...");
	}

	private String getNomeCampanha(String string) {
		String nomeCampanha = hashCampanha.get(string);
		return nomeCampanha == null ? "(desconhecido)" : nomeCampanha;
	}

	private boolean isIdCampanha(int i) {
		return i == 7;
	}

}
