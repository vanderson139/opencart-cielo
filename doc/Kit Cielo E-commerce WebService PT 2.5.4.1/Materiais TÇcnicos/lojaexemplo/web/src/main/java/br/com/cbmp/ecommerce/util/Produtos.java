package br.com.cbmp.ecommerce.util;

import java.util.Collection;
import java.util.LinkedHashMap;
import java.util.Map;

import br.com.cbmp.ecommerce.pedido.Produto;

public class Produtos {
	
	private static final Map<Long, Produto> produtos = new LinkedHashMap<Long, Produto>();
	
	static {
		produtos.put(88L, new Produto(88L, "Celular"));
		produtos.put(99L, new Produto(99L, "Celular"));
		produtos.put(589L, new Produto(589L, "iPhone"));
		produtos.put(55L, new Produto(55L, "Legacy"));
		produtos.put(0L, new Produto(0L, "Injeção"));
		produtos.put(852L, new Produto(852L, "TV 46'' LED"));
		produtos.put(8554L, new Produto(8554L, "Bala Chita"));
	}
	
	public static Produto recuperar(long id) {
		return produtos.get(id); 
	}
	
	public static Collection<Produto> todos() {
		return produtos.values();
	}
	
	
}
