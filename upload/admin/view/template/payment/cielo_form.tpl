<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-authorizenet-sim" data-toggle="tooltip"
                        title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
                   class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cielo" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="cielo_total" value="<?php echo $cielo_total; ?>" id="input-total" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-afiliacao"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo $help_afiliacao; ?>"><?php echo $entry_afiliacao; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="cielo_afiliacao" value="<?php echo $cielo_afiliacao; ?>" id="input-afiliacao" class="form-control" />
                            <?php if ($error_afiliacao) { ?>
                            <div class="text-danger"><?php echo $error_afiliacao; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-chave"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo $help_chave; ?>"><?php echo $entry_chave; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="cielo_chave" value="<?php echo $cielo_chave; ?>" id="input-chave" class="form-control" />
                            <?php if ($error_chave) { ?>
                            <div class="text-danger"><?php echo $error_chave; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo $help_teste; ?>"><?php echo $entry_teste; ?></span></label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <?php if ($cielo_teste) { ?>
                                <input type="radio" name="cielo_teste" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="cielo_teste" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                            </label>
                            <label class="radio-inline">
                                <?php if (!$cielo_teste) { ?>
                                <input type="radio" name="cielo_teste" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="cielo_teste" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cartao-visa"><?php echo $entry_cartao_visa; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_cartao_visa" id="input-cartao-visa" class="form-control">
                                <?php if ($cielo_cartao_visa) { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } else if (!$cielo_cartao_visa) { ?>
                                <option value="1"><?php echo $text_yes; ?></option>
                                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                <?php } else { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label" for="input-cartao-visa-parcelas"><?php echo $entry_parcelas; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="cielo_visa_parcelas" id="input-cartao-visa-parcelas" class="form-control" value="<?php echo $cielo_visa_parcelas ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cartao-mastercard"><?php echo $entry_cartao_mastercard; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_cartao_mastercard" id="input-cartao-mastercard" class="form-control">
                                <?php if ($cielo_cartao_mastercard) { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } else if (!$cielo_cartao_mastercard) { ?>
                                <option value="1"><?php echo $text_yes; ?></option>
                                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                <?php } else { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label" for="input-cartao-mastercard-parcelas"><?php echo $entry_parcelas; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="cielo_mastercard_parcelas" id="input-cartao-mastercard-parcelas" class="form-control" value="<?php echo $cielo_mastercard_parcelas ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cartao-diners"><?php echo $entry_cartao_diners; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_cartao_diners" id="input-cartao-diners" class="form-control">
                                <?php if ($cielo_cartao_diners) { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } else if (!$cielo_cartao_diners) { ?>
                                <option value="1"><?php echo $text_yes; ?></option>
                                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                <?php } else { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label" for="input-cartao-diners-parcelas"><?php echo $entry_parcelas; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="cielo_diners_parcelas" id="input-cartao-diners-parcelas" class="form-control" value="<?php echo $cielo_diners_parcelas ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cartao-discover"><?php echo $entry_cartao_discover; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_cartao_discover" id="input-cartao-discover" class="form-control">
                                <?php if ($cielo_cartao_discover) { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } else if (!$cielo_cartao_discover) { ?>
                                <option value="1"><?php echo $text_yes; ?></option>
                                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                <?php } else { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label" for="input-cartao-discover-parcelas"><?php echo $entry_parcelas; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="cielo_discover_parcelas" id="input-cartao-discover-parcelas" class="form-control" value="<?php echo $cielo_discover_parcelas ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cartao-elo"><?php echo $entry_cartao_elo; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_cartao_elo" id="input-cartao-elo" class="form-control">
                                <?php if ($cielo_cartao_elo) { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } else if (!$cielo_cartao_elo) { ?>
                                <option value="1"><?php echo $text_yes; ?></option>
                                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                <?php } else { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label" for="input-cartao-elo-parcelas"><?php echo $entry_parcelas; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="cielo_elo_parcelas" id="input-cartao-elo-parcelas" class="form-control" value="<?php echo $cielo_elo_parcelas ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cartao-amex"><?php echo $entry_cartao_amex; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_cartao_amex" id="input-cartao-amex" class="form-control">
                                <?php if ($cielo_cartao_amex) { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } else if (!$cielo_cartao_amex) { ?>
                                <option value="1"><?php echo $text_yes; ?></option>
                                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                <?php } else { ?>
                                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label" for="input-cartao-amex-parcelas"><?php echo $entry_parcelas; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="cielo_amex_parcelas" id="input-cartao-amex-parcelas" class="form-control" value="<?php echo $cielo_amex_parcelas ?>"/>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-cartao-semjuros"><?php echo $entry_cartao_semjuros; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="cielo_cartao_semjuros" id="input-cartao-semjuros" class="form-control" value="<?php echo $cielo_cartao_semjuros ?>"/>
                            <?php if ($error_cartao_semjuros) { ?>
                            <div class="text-danger"><?php echo $error_cartao_semjuros; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-cartao-minimo"><?php echo $entry_cartao_minimo; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="cielo_cartao_minimo" id="input-cartao-minimo" class="form-control" value="<?php echo $cielo_cartao_minimo ?>"/>
                            <?php if ($error_cartao_minimo) { ?>
                            <div class="text-danger"><?php echo $error_cartao_minimo; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-cartao-juros"><?php echo $entry_cartao_juros; ?></label>
                        <div class="col-sm-4">
                            <input type="text" name="cielo_cartao_juros" id="input-cartao-juros" class="form-control" value="<?php echo $cielo_cartao_juros ?>"/>
                            <?php if ($error_cartao_juros) { ?>
                            <div class="text-danger"><?php echo $error_cartao_juros; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-autorizacao"><?php echo $entry_autorizacao; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_autorizacao" id="input-autorizacao" class="form-control">
                                <option value="0" <?php if ($cielo_autorizacao == '0') { ?> selected="selected" <?php } ?> ><?php echo $text_nao_autorizar; ?></option>
                                <option value="1" <?php if ($cielo_autorizacao == '1') { ?> selected="selected" <?php } ?> ><?php echo $text_somente_autenticada; ?></option>
                                <option value="2" <?php if ($cielo_autorizacao == '2') { ?> selected="selected" <?php } ?> ><?php echo $text_autenticada_nao_autenticada; ?></option>
                                <option value="3" <?php if ($cielo_autorizacao == '3') { ?> selected="selected" <?php } ?> ><?php echo $text_sem_autenticacao; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-captura"><?php echo $entry_captura; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_captura" id="input-captura" class="form-control">
                                <option value="1" <?php if ($cielo_captura == '1') { ?>selected="selected"<?php } ?> ><?php echo $text_yes; ?></option>
                                <option value="0"><?php echo $text_no; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-parcelamento"><?php echo $entry_parcelamento; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_parcelamento" id="input-parcelamento" class="form-control">
                                <option value="2" <?php if ($cielo_parcelamento == '2') { ?> selected="selected" <?php } ?> ><?php echo $text_loja; ?></option>
                                <option value="3" <?php if ($cielo_parcelamento == '3') { ?> selected="selected" <?php } ?> ><?php echo $text_administradora; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-aprovado-id"><?php echo $entry_aprovado; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_aprovado_id" id="input-aprovado-id" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $cielo_aprovado_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"
                                        selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-nao-aprovado-id"><?php echo $entry_nao_aprovado; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_nao_aprovado_id" id="input-nao-aprovado-id" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $cielo_nao_aprovado_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"
                                        selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-capturado-id"><?php echo $entry_capturado; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_capturado_id" id="input-capturado-id" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $cielo_capturado_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"
                                        selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-cancelado-id"><?php echo $entry_cancelado; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_cancelado_id" id="input-cancelado-id" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $cielo_cancelado_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"
                                        selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_geo_zone_id" id="input-geo-zone" class="form-control">
                                <option value="0"><?php echo $text_all_zones; ?></option>
                                <?php foreach ($geo_zones as $geo_zone) { ?>
                                <?php if ($geo_zone['geo_zone_id'] == $cielo_geo_zone_id) { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"
                                        selected="selected"><?php echo $geo_zone['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-4">
                            <select name="cielo_status" id="input-status" class="form-control">
                                <option value="1" <?php if ($cielo_status) { ?> selected="selected" <?php } ?> ><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-4">
                            <input type="text" id="input-sort-order" name="cielo_sort_order" value="<?php echo $cielo_sort_order; ?>" class="form-control"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>