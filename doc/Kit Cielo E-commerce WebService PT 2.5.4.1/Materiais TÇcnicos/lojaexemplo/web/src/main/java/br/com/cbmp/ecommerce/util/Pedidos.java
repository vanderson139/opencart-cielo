package br.com.cbmp.ecommerce.util;

import java.util.Collection;
import java.util.Collections;
import java.util.LinkedList;

import br.com.cbmp.ecommerce.pedido.Pedido;

public class Pedidos {
	
	private LinkedList<Pedido> lista;
	
	public Pedidos() {
		lista = new LinkedList<Pedido>();		
	}
	
	public  void adicionar(Pedido pedido) {
		lista.add(pedido);
		if (lista.size() > 20) {
			lista.removeFirst();
		}
	}
	
	public Pedido recuperar(String numero) {
		for (Pedido pedido : lista) {
			if (pedido.getNumero().equals(numero)) {
				return pedido;
			}
		}
		return null;
	}
	
	public Collection<Pedido> todos() {
		return Collections.unmodifiableCollection(lista);
	}

}
