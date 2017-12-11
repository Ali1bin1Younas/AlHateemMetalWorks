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
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content add-voucher" style="background-color: #f9f9f9; box-shadow: inset 0px 1px 0px #fff; padding: 30px">
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
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="txtDateTimeCreated" style="width: 100px; margin-bottom: 0px; text-align: center" readonly="readonly" type="text" class="form-control">
                              </div>
                          </td>
                          <td style="padding-left: 10px">
                            <div class="form-group text-left">
                              <label>Delivery Date</label>
                              <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="txtDateTimeDelivery" style="width: 100px; margin-bottom: 0px; text-align: center" readonly="readonly" type="text" class="form-control">
                              </div>
                              </div>
                          </td>
                          <td style="padding-left: 10px">
                            <div class="form-group text-left">
                              <label>Reference</label>
                              <div class="input-group" style="margin-bottom: 0px">
                                <span class="input-group-addon">#</span>
                                <input id="txtReferenceNo" class="form-control input-sm" style="width: 80px; text-align: center" placeholder="Automatic" type="text">
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
                        <textarea class="form-control input-sm" style="width: 400px; height: 60px" data-bind="value: DeliveryAddress"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <table style="margin-left: -20px">
                      <thead>
                        <tr>
                          <th></th>
                          <th style="text-align: left; min-width: 150px"><label>Item</label></th>
                          <th style="text-align: left"><label>Description</label></th>
                          <th style="text-align: center"><label>Qty</label></th>
                          <th style="text-align: center"><label>Unit price</label></th>
                          <th style="text-align: center; display: none;" data-bind="visible: $root.Discount()"><label>Discount</label></th>
                          <th style="text-align: center"><label>Amount</label></th>
                        </tr>
                      </thead>
                      <tbody data-bind="sortable: { data: Lines, options: { handle: '.sortableHandle', cursor: 'move' } }" class="ko_container ui-sortable">
                        <tr data-select2height="46">
                          <td class="sortableHandle" style="cursor: move">
                            <img src="resources/webalys/_16px/interface-30.png" style="margin-right: 4px; opacity: 0;" data-bind="style: { opacity: ($root.Lines().length > 1) ? '0.25' : '0' }">
                          </td>
                          <td style="vertical-align: top; min-width: 150px">
                            <div class="controls select-picker-item">
                              <select class="selectpicker" id="ddlProducts" style="height:48px;" data-show-subtext="true" data-live-search="true">
                                <option>abc</option>
                              </select>
                            </div>
                          </td>
                          <td style="vertical-align: top">
                            <textarea class="form-control input-sm autosize" style="height: 48px; width: 300px; margin-bottom: 0px; resize: none; overflow: hidden; overflow-wrap: break-word;" data-bind="value: Description, autosize: Description" spellcheck="true"></textarea>
                          </td>
                          <td style="vertical-align: top">
                            <input class="regular form-control input-sm" style="width: 80px; text-align: center; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" data-bind="textInput: Qty" type="text">
                          </td>
                          <td style="vertical-align: top">
                            <input class="regular form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" data-bind="textInput: Amount" type="text">
                          </td>
                          <td style="vertical-align: top; display: none;" data-bind="visible: $root.Discount()">
                            <div class="input-group" style="margin-bottom: 0px" data-bind="visible: $root.DiscountType() == 'Percentage'">
                              <input class="regular form-control input-sm" style="width: 50px; text-align: center; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" placeholder="0" data-bind="textInput: Discount" type="text">
                              <span class="input-group-addon" style="vertical-align: top; padding-left: 5px; padding-right: 5px">%</span>
                            </div>
                            <input class="regular form-control input-sm" style="width: 80px; text-align: right; margin-bottom: 0px; line-height: 14px; display: none; height: 48px; padding-bottom: 24px;" placeholder="0" data-bind="textInput: DiscountAmount, visible: $root.DiscountType() == 'ExactAmount'" type="text">
                          </td>
                          <td style="vertical-align: top">
                            <div class="input-group" style="margin-bottom: 0px">
                              <input class="regular form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; line-height: 14px; height: 48px; padding-bottom: 24px;" readonly="readonly" data-bind="value: FormattedLineTotal" type="text">
                              <span class="input-group-addon input-sm" style="vertical-align: top; line-height: 14px; color: #999; text-shadow: 1px 1px 0px #fff" data-bind="text: function() { try { return $root.Supplier().currency; } catch(error) { return 'PKR'; } }()">PKR</span>
                              </div>
                            </td>
                            <td style="vertical-align: top; padding-left: 5px; padding-top: 5px">
                              <a href="#" class="close" style="font-size: 24px; float: none; display: none;" data-bind="click: $root.RemoveLines, visible: $root.Lines().length > 1">×</a>
                            </td>
                            <td style="vertical-align: top; padding-left: 10px" colspan="2" data-bind="if: (Account() != null &amp;&amp; Account().currency != null &amp;&amp; Account().currency != function() { try { return $root.Supplier().currency; } catch(error) { return 'PKR'; } }())">
                            </td>
                          </tr>
                        </tbody>
                      <tbody>
                        <tr>
                          <td></td>
                          <td class="pull-left">
                            <div class="btn-group" style=" margin-left: 3px"><button class="btn btn-default btn-xs" data-bind="click: AddLines">Add line</button><button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" style="min-width: 0px"><span class="caret"></span></button><div class="dropdown-backdrop"></div><ul class="dropdown-menu"><li><a href="#" data-bind="click: AddLines">Add line</a></li><li><a href="#" data-bind="click: Add5Lines">Add line (5×)</a></li><li><a href="#" data-bind="click: Add10Lines">Add line (10×)</a></li><li><a href="#" data-bind="click: Add20Lines">Add line (20×)</a></li></ul></div>
                          </td><td></td><td></td><td></td>
                          <td data-bind="visible: $root.Discount()" style="display: none;"></td>
                          <td>
                            <div class="input-group" style="margin-bottom: 0px">
                              <input class="form-control input-sm" style="width: 100px; text-align: right; margin-bottom: 0px; font-weight: bold" readonly="readonly" data-bind="value: function() { var total = 0; for (i = 0; i < $root.Lines().length; i++) { total += $root.Lines()[i].LineTotal(); } return Globalize.format(total, 'n'+total.getDecimals()); }()" type="text">
                              <span class="input-group-addon input-sm" style="color: #999; text-shadow: 1px 1px 0px #fff" data-bind="text: function() { try { return Supplier().currency; } catch(error) { return 'PKR'; } }()">PKR</span>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
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