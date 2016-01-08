<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-default"><i class="fa fa-cog"></i></a>
        <button type="submit" form="form-order-cielo" formaction="<?php echo $captura; ?>" data-toggle="tooltip" title="<?php echo $button_captura; ?>" class="btn btn-primary"><i class="fa fa-credit-card"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-order-cielo').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                      <label class="control-label" for="input-name"><?php echo $entry_order_id; ?></label>
                      <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-name" class="form-control" />
                  </div>
              </div>
              <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php foreach($status_options as $value => $desc) { ?>
                    <option value="<?php echo $value ?>"><?php echo $desc ?></option>
                    <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $cancel; ?>" method="post" enctype="multipart/form-data" id="form-order-cielo">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                    <td class="text-left"><?php if ($sort == 'o.order_id') { ?>
                        <a href="<?php echo $sort_order_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_order_id; ?>"><?php echo $column_order_id; ?></a>
                        <?php } ?>
                    </td>
                    <td class="text-left"><?php if ($sort == 'o.total') { ?>
                        <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                        <?php } ?>
                    </td>
                    <td class="text-left"><?php if ($sort == 'c.firstname') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?>
                  </td>
                    <td class="text-left"><?php if ($sort == 'oc.autorizacao_data') { ?>
                        <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
                        <?php } ?>
                    </td>
                  <td class="text-left"><?php if ($sort == 'oc.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?>
                  </td>
                    <td class="text-left"><?php if ($sort == 'prazo_captura') { ?>
                        <a href="<?php echo $sort_prazo_captura; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_prazo_captura; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_prazo_captura; ?>"><?php echo $column_prazo_captura; ?></a>
                        <?php } ?>
                    </td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($transactions) { ?>
                <?php foreach ($transactions as $transaction) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($transaction['order_cielo_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $transaction['order_cielo_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $transaction['order_cielo_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $transaction['order_id']; ?></td>
                  <td class="text-left"><?php echo $transaction['total']; ?></td>
                  <td class="text-left"><?php echo $transaction['name']; ?></td>
                  <td class="text-left"><?php echo $transaction['date_modified']; ?></td>
                  <td class="text-left"><?php echo $transaction['status']; ?></td>
                  <td class="text-left"><?php echo $transaction['prazo_captura']; ?></td>
                  <td class="text-right">
                      <a href="<?php echo $transaction['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a>
                      <a href="<?php echo $transaction['customer']; ?>" data-toggle="tooltip" title="<?php echo $button_customer; ?>" class="btn btn-success"><i class="fa fa-user"></i></a>
                      <?php if($transaction['status_code'] == '4') { ?><a href="<?php echo $transaction['captura']; ?>" data-toggle="tooltip" title="<?php echo $button_captura; ?>" class="btn btn-primary"><i class="fa fa-credit-card"></i></a><?php } ?>
                      <?php if($transaction['status_code'] != '9') { ?><a href="<?php echo $transaction['cancel']; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a><?php } ?>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  var url = 'index.php?route=payment/cielo&token=<?php echo $token; ?>';

    var filter_order_id = $('input[name=\'filter_order_id\']').val();

    if (filter_order_id) {
        url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
    }

    var filter_name = $('input[name=\'filter_name\']').val();

  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }

  var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }

  location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'] + ' ('+ item['email'] +')',
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_name\']').val(item['label']);
  }
});
//--></script></div>
<?php echo $footer; ?>