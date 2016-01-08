<?php
// Heading
$_['heading_title']              = 'Cielo';

$_['button_view']              = 'Ver Pedido';
$_['button_customer']              = 'Ver Cliente';
$_['button_captura']              = 'Capturar';

// Text
$_['text_payment']               = 'Formas de Pagamento';
$_['text_success']               = 'Módulo Cielo atualizado com sucesso!';
$_['text_cielo']                 = '<a onclick="window.open(\'http://www.cielo.com.br/portal/cielo/solucoes-de-tecnologia/e-commerce.html\');"><img src="view/image/payment/cielo.jpg" alt="cielo" title="cielo" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_list']                 = 'Transações';
$_['text_edit']                 = 'Editar';
$_['text_loja']                  = 'Loja';
$_['text_administradora']        = 'Administradora';
$_['text_cielo_configure']  = 'Configurar Módulo';
$_['text_cielo_transactions']   = 'Transações';

$_['column_order_id']        = 'Pedido nº';
$_['column_name']        = 'Cliente';
$_['column_total']        = 'Valor';
$_['column_date']        = 'Data';
$_['column_prazo_captura']        = 'Prazo para captura';
$_['column_status']        = 'Situação';
$_['column_action']        = 'Ação';

// Entry
$_['entry_order_id']             = 'Pedido nº';
$_['entry_name']             = 'Cliente';
$_['entry_total']                = 'Total';
$_['entry_afiliacao']            = 'Afiliação';
$_['entry_chave']                = 'Chave';
$_['entry_teste']                = 'Modo Teste';
$_['entry_cartao_visa']          = 'Ativar Visa';
$_['entry_cartao_visae']         = 'Ativar Visa Electron';
$_['entry_cartao_mastercard']    = 'Ativar Mastercard';
$_['entry_cartao_diners']        = 'Ativar Diners';
$_['entry_cartao_discover']      = 'Ativar Discover';
$_['entry_cartao_elo']           = 'Ativar Elo';
$_['entry_cartao_amex']          = 'Ativar American Express';
$_['entry_parcela_maximo']             = 'Nº Máx. de Parcelas ';
$_['entry_avs']         = 'Ativar a Verificação de Endereço (AVS)';
$_['entry_analise_risco']         = 'Ativar a Análise de Risco';
$_['entry_parcelamento']         = 'Parcelamento pela';
$_['entry_parcela_semjuros']      = 'Nº de Parcelas sem Juros';
$_['entry_parcela_minimo']        = 'Valor mínimo por parcela';
$_['entry_parcela_juros']         = 'Taxa de Juros Mensal (%)';
$_['entry_aprovado']             = 'Situação do Pedido se Aprovado';
$_['entry_nao_aprovado']         = 'Situação do Pedido se Não Aprovado';
$_['entry_capturado']            = 'Situação do Pedido se Capturado';
$_['entry_cancelado']            = 'Situação do Pedido se Cancelado';
$_['entry_geo_zone']             = 'Região Geográfica';
$_['entry_status']               = 'Situação';
$_['entry_sort_order']           = 'Ordem';
$_['entry_captura']           = 'Captura Automática:';
$_['entry_autorizacao']           = 'Modo de Autorização';
$_['text_nao_autorizar']           = 'Não autorizar (somente autenticar)';
$_['text_somente_autenticada']           = 'Autorizar somente se for autenticada';
$_['text_autenticada_nao_autenticada']           = 'Autorizar autenticada e não autenticada';
$_['text_sem_autenticacao']           = 'Autorizar sem passar por autentição (usada somente para cartões de crédito)';

$_['cielo_status_0']           = 'Criada';
$_['cielo_status_1']           = 'Em andamento';
$_['cielo_status_2']           = 'Autenticada';
$_['cielo_status_3']           = 'Não autenticada';
$_['cielo_status_4']           = 'Autorizada';
$_['cielo_status_5']           = 'Não autorizada';
$_['cielo_status_6']           = 'Capturada';
$_['cielo_status_8']           = 'Não capturada';
$_['cielo_status_9']           = 'Cancelada';
$_['cielo_status_10']          = 'Em autenticação';

$_['help_total']             = 'O total que o pedido deve alcançar para que este método de pagamento seja ativado.';
$_['help_afiliacao']             = 'Seu número de afiliação junto a Cielo.';
$_['help_chave']             = 'Sua chave fornecida pela Cielo.';
$_['help_teste']             = 'Ambiente de trabalho de sua loja.';

// Error
$_['error_permission']           = 'Atenção: Você não possui permissão para modificar o módulo Cielo!';
$_['error_afiliacao']            = 'Atenção: Informe corretamente o número da sua afiliação junto a Cielo!';
$_['error_chave']                = 'Atenção: Informe corretamente sua chave de acesso!';
$_['error_parcela_semjuros']      = 'Atenção: Informe corretamente quantidade de parcelas sem juros!';
$_['error_parcela_minimo']        = 'Atenção: O valor mínimo de cada parcela não pode ser inferior a 5!';
$_['error_parcela_juros']         = 'Atenção: Informe o corretamente a taxa de juros!';
$_['error_order_cielo_id']         = 'Atenção: Selecione ao menos uma transação para executar esta operação';
