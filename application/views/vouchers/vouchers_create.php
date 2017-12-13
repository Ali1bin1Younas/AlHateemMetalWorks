<style>
  .add-voucher{
    font-size:12px;
  }
  .select-picker-item > .bootstrap-select > .btn {
    height: 48px;
}
</style>
<link href="<?php echo base_url(); ?>Assets/css/plugins/bootstrap-select/bootstrap-select.css" rel="stylesheet">
<!-- Content Wrapper. Contains page content -->
<div class="fadeInRight animated">
  <hr class="hr-success" />
  <div class="content-wrapper" id="createVoucherView">
    <!-- Main content -->
    <section class="content add-voucher">
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
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="txtDateTimeCreated" data-bind="textInput: issueDate" style="width: 100px; margin-bottom: 0px; text-align: center" readonly="readonly" type="text" class="form-control">
                              </div>
                          </td>
                          <td style="padding-left: 10px">
                            <div class="form-group text-left">
                              <label>Delivery Date</label>
                              <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="txtDateTimeDelivery" data-bind="textInput: deliveryDate" style="width: 100px; margin-bottom: 0px; text-align: center" readonly="readonly" type="text" class="form-control">
                              </div>
                              </div>
                          </td>
                          <td style="padding-left: 10px">
                            <div class="form-group text-left">
                              <label>Reference</label>
                              <div class="input-group" style="margin-bottom: 0px">
                                <span class="input-group-addon">#</span>
                                <input id="txtReferenceNo" class="form-control input-sm" style="width: 80px; text-align: center" data-bind="textInput: referenceNo" placeholder="Automatic" type="text">
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="form-group col-sm-12">
                    <div class="row-fluid pull-left text-left">
                      <label>Supplier</label>
                      <div class="controls">
                        <select class="selectpicker" id="ddlUsers" data-show-subtext="true" data-live-search="true">
                        <option>abc</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-sm-12">
                    <div class="pull-left text-left">
                      <label>Description</label>
                      <div class="controls">
                        <textarea class="form-control input-sm" style="width: 400px; height: 60px" data-bind="value: VoucherDescription"></textarea>
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
                          <th style="text-align: center; display: none;" data-bind="visible: $root.discount()"><label>Discount</label></th>
                          <th style="text-align: center"><label>Amount</label></th>
                        </tr>
                      </thead>
                      <tbody class="ko_container ui-sortable" data-bind="sortable: { data: Lines, options: { handle: '.sortableHandle', cursor: 'move' } }">
                        <tr data-select2height="46">
                          <td style="vertical-align: top; min-width: 150px">
                            <div class="controls select-picker-item">
                              <select class="selectpicker" id="ddlProducts" style="height:48px;" data-show-subtext="true" data-live-search="true">
                                <option>abc</option>
                              </select>
                            </div>
                          </td>
                          <td style="vertical-align: top">
                            <textarea class="form-control input-sm autosize" style="height: 48px; width: 300px; margin-bottom: 0px; resize: none; overflow: hidden; overflow-wrap: break-word;" data-bind="value: description" spellcheck="true"></textarea>
                          </td>
                          <td style="vertical-align: top">
                            <input class="regular form-control input-sm" style="width: 80px; text-align: center; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" data-bind="textInput: qty" type="text">
                          </td>
                          <td style="vertical-align: top">
                            <input class="regular form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" data-bind="textInput: amount" type="text">
                          </td>
                          <td style="vertical-align: top; display: none;" data-bind="visible: $root.discount()">
                            <div class="input-group" style="margin-bottom: 0px" data-bind="visible: $root.discountType() == 'Percentage'">
                              <input class="regular form-control input-sm" style="width: 50px; text-align: center; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" placeholder="0" data-bind="textInput: discount" type="text">
                              <span class="input-group-addon" style="vertical-align: top; padding-left: 5px; padding-right: 5px">%</span>
                            </div>
                            <input class="regular form-control input-sm" style="width: 80px; text-align: right; margin-bottom: 0px; line-height: 14px; display: none; height: 48px; padding-bottom: 24px;" placeholder="0" data-bind="textInput: discountAmount, visible: $root.discountType() == 'ExactAmount'" type="text">
                          </td>
                          <td style="vertical-align: top">
                            <div class="input-group" style="margin-bottom: 0px">
                              <input class="regular form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" readonly="readonly" data-binding="" type="text">
                              <span class="input-group-addon input-sm" style="vertical-align: top; line-height: 14px; color: #999; text-shadow: 1px 1px 0px #fff">PKR</span>
                              </div>
                            </td>
                            <td style="vertical-align: top; padding-left: 5px; padding-top: 5px">
                              <a href="#" class="close" style="font-size: 24px; float: none; display: none;" data-bind="click: $root.RemoveLines, visible: $root.Lines().length > 1">×</a>
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
                          </td><td></td><td></td><td></td>
                          <td data-bind="visible: $root.discount()" style="display: none;"></td>
                          <td>
                            <div class="input-group" style="margin-bottom: 0px">
                              <input class="form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; font-weight: bold" readonly="readonly" type="text">
                              <span class="input-group-addon input-sm" style="color: #999; text-shadow: 1px 1px 0px #fff">PKR</span>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="form-group col-sm-12">
                    <div class="checkbox">
                      <label><input data-bind="checked: discount" type="checkbox">Discount</label>
                      <div class="form-group" data-bind="visible: $root.discount()" style="display: none;">
                        <select class="selectpicker" data-bind="value: discountType" id="ddlProducts" style="width: 200px;" data-show-subtext="true" data-live-search="true">
                          <option value="Percentage">Percentage</option>
                          <option value="ExactAmount">Exact amount</option>
                        </select>
                      </div> 
                  </div>
                </div>
              </div>
              <div class="panel-footer">
                <img src="resources/ajax-loader.gif" id="ajaxIndicator" style="display: none; margin-right: 10px">
                <div class="btn-group">
                  <input class="btn btn-primary" data-bind="click: createVoucher" style="font-weight: bold" value="Create" type="button">
                  <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <input id="btnCreateAndAddAnother" class="btn btn-link" value="Create &amp; add another" type="button">
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div><!-- /.box-body -->
        </div>
      </div> 
    </div>
  </section>
    <!-- /.content -->
</div>
 <!-- /.content-wrapper -->
</div>
