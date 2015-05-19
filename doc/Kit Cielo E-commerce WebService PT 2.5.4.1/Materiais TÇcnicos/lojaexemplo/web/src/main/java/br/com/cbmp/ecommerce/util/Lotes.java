package br.com.cbmp.ecommerce.util;

import java.util.Collection;
import java.util.Collections;
import java.util.LinkedList;

import br.com.cbmp.ecommerce.pedido.Lote;

public class Lotes {
	
	private LinkedList<Lote> lista;
	
	public Lotes() {
		this.lista = new LinkedList<Lote>();
	}
	public  void adicionar(Lote lote) {
		Lote lte = null;
		for (Lote l : lista) {
			if(l.getNumeroLote() == lote.getNumeroLote()){
				lte = l;
				break;
			}
		}
		
		if(lte != null){
			lte.setName(lote.getName());
			lte.setXmlRetorno(lote.getXmlRetorno());
			lte.setPath(lote.getPath());
		} else {
			lista.add(lote);
		}
		
		if (lista.size() > 20) {
			lista.removeFirst();
		}
	}
	
	public Lote recuperar(long numero) {
		for (Lote lote : lista) {
			if (lote.getNumeroLote() == numero) {
				return lote;
			}
		}
		return null;
	}
	
	public Collection<Lote> todos() {
		return Collections.unmodifiableCollection(lista);
	}

}
