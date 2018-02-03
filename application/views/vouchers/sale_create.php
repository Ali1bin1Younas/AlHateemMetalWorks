<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url(); ?>Assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/bootstrap-select/bootstrap-select.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/select2/select2.min.css" rel="stylesheet">
<style>
  .swal-wider-voucher{
    width:1000px !important;
    max-height:700px !important;
  }
  .add-voucher{
    font-size:12px;
  }
  .select-picker-item > .bootstrap-select > .btn {
    height: 48px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="fadeInRight animated">
  <div class="panel panel-default" style="margin-top:10px;">
    <div class="panel-heading">
      <span class="header">
        <h3>
        <img style="width:30px; cursor:pointer;" onclick="window.history.back();" src="<?php echo base_url(); ?>Assets/img/custom/if_arrow-left.png"/>
          <?php echo $pageHeading; ?>
        </h3>
      </span>
    </div>
    <div class="panel-body">
        <div class="row">
          <div class="col-xs-12 rem">
            <div class="box">
              <div class="box-body">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="col-sm-12">
                      <table>
                        <tbody>
                          <tr>
                            <td>
                              <div class="form-group text-left">
                                <label>Issue date</label>
                                <div class="input-group date">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input data-bind="textInput: issueDate" style="width: 100px; margin-bottom: 0px; text-align: center" readonly="readonly" type="text" class="form-control">
                                </div>
                            </td>
                            <td style="padding-left: 10px">
                              <div class="form-group text-left">
                                <label>Delivery Date</label>
                                <div class="input-group date">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input data-bind="textInput: deliveryDate" style="width: 100px; margin-bottom: 0px; text-align: center" readonly="readonly" type="text" class="form-control">
                                </div>
                                </div>
                            </td>
                            <td style="padding-left: 10px">
                              <div class="form-group text-left">
                                <label>Reciept</label>
                                <div class="input-group" style="margin-bottom: 0px">
                                  <span class="input-group-addon">#</span>
                                  <input class="form-control input-sm" style="width: 80px; text-align: center" data-bind="textInput: saleNo" type="text" readonly="readonly">
                                  <input id="txtSaleNo" type="hidden" value="<?php echo $saleNo;?>"
                                </div>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="form-group col-sm-3">
                      <div class="row-fluid pull-left text-left">
                        <label>Customers</label>
                        <div class="controls">
                          <input data-bind="ddlSelect2: customer" data-autocomplete="getCustomers" data-width="250px"  title="" />
                        </div>
                      </div>
                    </div>
                    <div class="form-group col-sm-9">
                      <div class="pull-left text-left">
                        <label>Description</label>
                        <div class="controls">
                          <input class="form-control input-sm" style="width: 400px;" data-bind="textInput: VoucherDescription"/>
                        </div>
                      </div>
                    </div>
                    <div class="form-group col-sm-12">
                      <table>
                        <thead>
                          <tr>
                            <th style="text-align: left; min-width: 150px"><label>Item</label></th>
                            <th style="text-align: left"><label>Description</label></th>
                            <th style="text-align: center"><label>Qty</label></th>
                            <th style="text-align: center"><label>Unit price</label></th>
                            <th style="text-align: center"><label>Builty No</label></th>
                            <th style="text-align: center"><label>Cargo</label></th>
                            <th style="text-align: center; display: none;" data-bind="visible: $root.discount()"><label>Discount</label></th>
                            <th style="text-align: center"><label>Amount</label></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="pull-left">
                            </td><td></td><td></td><td></td><td></td><td></td>
                            <td data-bind="visible: $root.discount()" style="display: none;"></td>
                            <td>
                              <div class="input-group" style="margin-bottom: 0px">
                                <input data-bind="value: function() { var total = 0; for (i = 0; i < $root.Lines().length; i++) { total += $root.Lines()[i].LineTotal(); } return Globalize.format(total, 'n'+total.getDecimals()); }()" class="form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; font-weight: bold" readonly="readonly" type="text">
                                <span class="input-group-addon input-sm" style="color: #999; text-shadow: 1px 1px 0px #fff">PKR</span>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                        <tbody data-bind="sortable: { data: Lines, options: { handle: '.sortableHandle', cursor: 'move' } }" class="ko_container ui-sortable">
                          <tr data-select2height="46">
                            <td style="vertical-align: top; min-width: 150px">
                              <div class="controls select-picker-item">
                                <input data-bind="ddlSelect2: Item" data-autocomplete="getProducts" data-width="100%"  title="" />
                              </div>
                            </td>
                            <td style="vertical-align: top">
                              <textarea data-bind="value: description" spellcheck="true" class="form-control input-sm autosize" style="height: 48px; width: 300px; margin-bottom: 0px; resize: none; overflow: hidden; overflow-wrap: break-word;" ></textarea>
                            </td>
                            <td style="vertical-align: top">
                              <input data-bind="textInput: qty" type="text" class="regular form-control input-sm" style="width: 80px; text-align: center; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" >
                            </td>
                            <td style="vertical-align: top">
                              <input data-bind="textInput: amount" type="text" class="regular form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" >
                            </td>
                            <td style="vertical-align: top">
                              <input data-bind="textInput: builtyNo" type="text" class="regular form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" >
                            </td>
                            <td style="vertical-align: top">
                              <input data-bind="textInput: cargoName" type="text" class="regular form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" >
                            </td>
                            <td style="vertical-align: top; display: none;" data-bind="visible: $root.discount()">
                              <div class="input-group" style="margin-bottom: 0px" data-bind="visible: $root.discountType() == 'Percentage'">
                                <input data-bind="textInput: discount" placeholder="0" type="text" class="regular form-control input-sm" style="width: 50px; text-align: center; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" >
                                <span class="input-group-addon" style="vertical-align: top; padding-left: 5px; padding-right: 5px">%</span>
                              </div>
                              <input data-bind="textInput: discountAmount, visible: $root.discountType() == 'ExactAmount'" placeholder="0" type="text"class="regular form-control input-sm" style="width: 80px; text-align: right; margin-bottom: 0px; line-height: 14px; display: none; height: 48px; padding-bottom: 24px;" >
                            </td>
                            <td style="vertical-align: top">
                              <div class="input-group" style="margin-bottom: 0px">
                                <input data-bind="value: FormattedLineTotal" type="text" class="regular form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" readonly="readonly" >
                                <span class="input-group-addon input-sm" style="vertical-align: top; line-height: 14px; color: #999; text-shadow: 1px 1px 0px #fff">PKR</span>
                                </div>
                              </td>
                              <td style="vertical-align: top; padding-left: 5px; padding-top: 5px">
                                <a data-bind="click: $root.RemoveLines, visible: $root.Lines().length > 1" href="#" class="close" style="font-size: 24px; float: none; display: none;">×</a>
                              </td>
                              <td style="vertical-align: top; padding-left: 10px" colspan="2">
                              </td>
                          </tr>
                        </tbody>
                        <tbody>
                          <tr>
                            <td class="pull-left">
                              <div class="btn-group" style=" margin-left: 3px">
                                <button class="btn btn-default btn-xs" data-bind="click: AddLines">Add line</button>
                                <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" style="min-width: 0px">
                                  <span class="caret"></span>
                                </button><div class="dropdown-backdrop">
                              </div>
                              <ul class="dropdown-menu">
                                <li><a href="#" data-bind="click: AddLines">Add line</a></li>
                                <li><a href="#" data-bind="click: Add5Lines">Add line (5×)</a></li>
                                <li><a href="#" data-bind="click: Add10Lines">Add line (10×)</a></li>
                                <li><a href="#" data-bind="click: Add20Lines">Add line (20×)</a></li>
                              </ul>
                            </div>
                            </td><td></td><td></td><td></td><td></td><td></td>
                            <td data-bind="visible: $root.discount()" style="display: none;"></td>
                            <td>
                              <div class="input-group" style="margin-bottom: 0px">
                                <input class="form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; font-weight: bold" readonly="readonly" type="text" data-bind="value: function() { var total = 0; for (i = 0; i < $root.Lines().length; i++) { total += $root.Lines()[i].LineTotal(); } return Globalize.format(total, 'n'+total.getDecimals()); }()">
                                <span class="input-group-addon input-sm" style="color: #999; text-shadow: 1px 1px 0px #fff">PKR</span>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="form-group col-sm-12">
                      <div class="checkbox pull-left">
                        <label class="pull-left"><input data-bind="checked: discount" type="checkbox">Discount</label>
                        <div class="form-group" data-bind="visible: $root.discount()" style="display: none;">
                          <select class="selectpicker" data-bind="value: discountType" id="ddlProducts" style="width: 200px;" data-show-subtext="true" data-live-search="true">
                            <option value="Percentage">Percentage</option>
                            <option value="ExactAmount">Exact amount</option>
                          </select>
                        </div> 
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- /.box-body -->
          </div>
        </div>
    </div>
    <div class="panel-footer" style="padding: 15px 30px">
      <img src="<?php echo base_url(); ?>assets/img/ajax-loader.gif" data-bind="enable: LoadingVoucherEnable" id="ajaxIndicator" style="display: none; margin-right: 10px" />
      <div class="btn-group">
        <input class="btn btn-primary" data-bind="click: createVoucher, enable: createVoucherEnable" style="font-weight: bold" value="Create" type="button">
        <button class="btn btn-primary dropdown-toggle" data-bind="enable: createVoucherEnable" data-toggle="dropdown">
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li>
            <input class="btn btn-link" data-bind="click: createVoucherNew" value="Create &amp; add another" type="button">
          </li>
        </ul>
      </div>
      <input id="callBackLoc" value="<?php echo base_url('sale');?>" type="hidden">
    </div>
  </div>
</div>
<?php  $this->load->view('common/footer');  ?>

<!--Page Scripts -->
<script src="<?php echo base_url(); ?>Assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/sweetalert/sweetalert2.0.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/bootstrap-select/bootstrap-select.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/globalize.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/knockoutJS/knockout-3.4.2.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/knockoutJS/knockout-sortable.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/custum/sale_create.js"></script>
<!-- Page-Level Scripts -->
</body>
</html>