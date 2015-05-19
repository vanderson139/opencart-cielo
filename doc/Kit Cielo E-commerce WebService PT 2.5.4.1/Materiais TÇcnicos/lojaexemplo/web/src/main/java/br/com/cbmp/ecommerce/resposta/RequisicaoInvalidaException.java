package br.com.cbmp.ecommerce.resposta;


public class RequisicaoInvalidaException extends RuntimeException {
	
	private static final long serialVersionUID = -8996081385394115826L;
	
	private Erro erro;
	
	public RequisicaoInvalidaException(Erro erro) {
		super("[código='" + erro.getCodigo() + "', mensagem='" + erro.getMensagem() + "']");
		this.erro = erro;
	}

	public Erro getErro() {
		return erro;
	}

}
