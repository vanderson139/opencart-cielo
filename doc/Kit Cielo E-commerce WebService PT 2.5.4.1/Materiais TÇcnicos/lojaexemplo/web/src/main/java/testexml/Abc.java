package testexml;

import java.util.ArrayList;
import java.util.List;

import br.com.cbmp.ecommerce.resposta.Erro;

import com.thoughtworks.xstream.XStream;

public class Abc {

	
	static class ObjetoPai {
		
		private List<String> arrayJobs = new ArrayList<String>();
		
	}
	
	public static void main(String[] args) {
		
		XStream xStream = new XStream();
		xStream.alias("arrayJobs", ArrayList.class);
		
		ObjetoPai obj = new ObjetoPai();
		
		
		ArrayList<String> lista = new ArrayList<String>();
		
		String xml = xStream.toXML(lista);
				
		
		System.out.println(xml);
		
		
		
	}
}
