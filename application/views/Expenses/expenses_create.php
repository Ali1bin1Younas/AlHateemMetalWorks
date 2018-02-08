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
  <div class="panel panel-default" style="margin-top:10px;" id="createExpenseView">
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
                                <label>Date</label>
                                <div class="input-group date">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input data-bind="textInput: dateCreated" style="width: 100px; margin-bottom: 0px; text-align: center" readonly="readonly" type="text" class="form-control">
                                </div>
                            </td>
                            <td style="padding-left: 10px">
                              <div class="form-group text-left">
                                <label>No</label>
                                <div class="input-group" style="margin-bottom: 0px">
                                  <span class="input-group-addon">#</span>
                                  <input class="form-control input-sm" style="width: 80px; text-align: center" data-bind="textInput: ID" type="text" readonly="readonly">
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
                          <input data-bind="ddlSelect2: customer" data-autocomplete="<?php echo base_url(); ?>Sale/getCustomers" data-width="250px"  title="" />
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
                            <th style="text-align: center"><label>Amount</label></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="pull-left">
                            </td><td></td><td></td><td></td>
                            <td style="display: none;"></td>
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
                                <input data-bind="ddlSelect2: Item" data-autocomplete="<?php echo base_url(); ?>Expenses/getProducts" data-width="100%"  title="" />
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
                            </td><td></td><td></td><td></td>
                            <td style="display: none;"></td>
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
      <input id="txtNo" type="hidden" value="<?php echo $No;?>">
      <input id="txtIsEdit" type="hidden" value="<?php echo $isEdit;?>">
      <input id="callBackLoc" value="<?php echo base_url('Expenses');?>" type="hidden">
    </div>
  </div>
</div>
<?php  $this->load->view('common/footer');  ?>

<!--Page Scripts -->
<script src="<?php echo base_url(); ?>Assets/js/load.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/sweetalert/sweetalert2.0.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/bootstrap-select/bootstrap-select.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/globalize.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/knockoutJS/knockout-3.4.2.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/knockoutJS/knockout-sortable.js"></script>
<!-- Page-Level Scripts -->
<script type="text/javascript">
  var bs_url = "<?php echo base_url();?>";
  load(bs_url+'Assets/js/plugins/select2/select2.full.min.js').then(bs_url+'Assets/js/custum/sale_create.js').thenRun(function () {
  
    ////////////////////////////////////////////////
   //     setting up ViewModel using KnowkoutJS   /
  ////////////////////////////////////////////////
  var viewModelInit = true;
  Number.prototype.getDecimals = function() {
      var num = parseFloat(this.toFixed(10));
      var match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
      if (!match)
          return 0;
      return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
  }
  ko.bindingHandlers.ddlSelect2 = {
      init: function(element, valueAccessor, allBindingsAccessor) {
          
              var select2options = {};
          if ($(element).is('select'))
          {
              select2options.width = $(element).attr('data-width');
              select2options.allowClear = ($(element).attr('data-placeholder'));
              select2options.formatNoMatches = function() { return "No matches found"; };
              if (select2options.allowClear) select2options.placeholder = $(element).attr('data-placeholder');
          }
          if ($(element).is('input'))
          {
              try{
                  var select2options = {
                  allowClear: true,
                  placeholder: ' ',
                  formatNoMatches: function() { return "No matches found"; },
                  formatSearching: function() { return "Searching ..."; },
                  ajax:
                  {
                      url: $(element).attr('data-autocomplete'),
                      dataType: 'json',
                      width: 'copy',
                      data: function (term, page) { return { Term: term } },
                      results: function(data, page) { return data; }
                  }};
              select2options.width = $(element).attr('data-width');
              }catch(e){alert(e.message);}
          }

          try{
              $(element).select2(select2options);
          }catch(e){alert(e.message);}
          var tr = $(element).select2('container').closest('tr');
          if (tr.attr('data-select2height'))
          {
              $(element).select2('container').find('.select2-choice').height(tr.attr('data-select2height'));
          }

          ko.utils.registerEventHandler(element, 'change', function ()
          {
              var observable = valueAccessor();
              var data = $(element).select2('data');
              if (data != null)
              {
                  data = jQuery.extend(true, {}, data);
                  delete data.element;
                  delete data.disabled;
                  delete data.locked;
              }
              observable(data);
          });

          ko.utils.domNodeDisposal.addDisposeCallback(element, function() {
              $(element).select2('destroy');
          });
          
      },update: function(element, valueAccessor) {
          var data = ko.utils.unwrapObservable(valueAccessor());
          if ($(element).is('input'))
          {
              $(element).select2('data', data);
          }
          if ($(element).is('select'))
          {
              if (data == null) $(element).select2('val', '');
              else $(element).select2('val', data.id);
          }
      }
  };
  ko.bindingHandlers.autosize = {
      update: function (element, valueAccessor) {
          ko.utils.unwrapObservable(valueAccessor());
          $(element).trigger('autosize.resize');
      }
  };
  function transactionLinesModel(){
      var self = this;
      self.Item = ko.observable();
      self.trackingCode = ko.observable(); 
      self.qty = ko.observable();
      self.amount = ko.observable();
      self.description = ko.observable();

      self.Item.subscribe(function(data) { 
          if (data && data.description && data.description.length > 0) { 
              self.description(data.description); } 
          if (data && data.unitPrice && data.unitPrice.length > 0) { 
              self.amount(data.unitPrice); } 
          if (data && data.trackingCode && data.trackingCode.length > 0) { 
              self.trackingCode({ id: data.trackingCode }); 
          } 
          if (self.qty() == null || self.qty().length == 0) { 
              self.qty('1'); 
          } 
      });
      self.AmountAsNumber = ko.computed(function() { 
          var amount = Globalize.parseFloat((self.amount() || '').toString()); 
          if (isNaN(amount)) { amount = 0; }; 
          return amount; 
      });
      self.LineTotal = ko.computed(function() { 
          var qty = Globalize.parseFloat((self.qty() || '').toString()); 
          var amount = Globalize.parseFloat((self.amount() || '').toString()); 
          if (isNaN(qty)) { qty = 1; }; 
          if (isNaN(amount)) { amount = 0; }; 
          var subtotal = qty*amount; 
          return subtotal; 
      });
      self.FormattedLineTotal = ko.computed(function() { 
          var total = self.LineTotal(); 
          return Globalize.format(total, 'n'+total.getDecimals());
      });
  }
  function ReservationsViewModel() {
      var self = this;
      self.isEdit = ko.observable();
      self.ID = ko.observable();

      self.Lines = ko.observableArray();
      
      self.dateCreated = ko.observable();
      self.VoucherDescription = ko.observable();
      self.customer = ko.observable();

      self.description = ko.observable();
      self.GradeTotal = ko.observable();
      self.qty = ko.observable();
      self.amount = ko.observable();
      self.LoadingVoucherEnable = ko.observable();
      self.GradeTotal = ko.computed(function(data) { 
          var total = 0; 
          for (i = 0; i < self.Lines().length; i++) { total += self.Lines()[i].LineTotal(); } 
          return Globalize.format(total, 'n'+total.getDecimals()); 
      });
      self.AddLines = function() {self.Lines.push(new transactionLinesModel());};
      self.Add5Lines = function() { 
          for (var i = 0; i < 5; i++) 
              self.Lines.push(new transactionLinesModel()); 
      }; 
      self.Add10Lines = function() { 
          for (var i = 0; i < 10; i++) 
              self.Lines.push(new transactionLinesModel()); 
      }; 
      self.Add20Lines = function() { 
          for (var i = 0; i < 20; i++) 
          self.Lines.push(new transactionLinesModel()); 
      };
      self.RemoveLines = function(line) { self.Lines.remove(line); };
      self.createVoucherEnable = ko.computed(function() {
          if(ko.toJSON(self.dateCreated) != "" && ko.toJSON(self.customer) != undefined && ko.toJSON(self.customer) != "null"){return true;}
          else{return false;}
      });
      self.createVoucher = function(){
          self.LoadingVoucherEnable(true);
          $.ajax({
              url: 'add_record',
              method: 'GET',
              contentType: "application/json; charset:utf-8",
              dataType: 'json',
              data: {'model': ko.toJSON(this)},
              success: onSuccess_add_record(this, 0),
              error: function (res) {
                  swal("Upexpected Error", "Please contact system administrator.", "error");
              },
              failure: function (res) {
                  swal("Upexpected Error", "Please try again later.", "error");
              }
          });
      };
      self.createVoucherNew = function(){
          self.LoadingVoucherEnable(true);
          $.ajax({
              url: 'add_record',
              method: 'GET',
              contentType: "application/json; charset:utf-8",
              dataType: 'json',
              data: {'model': ko.toJSON(this)},
              success: onSuccess_add_record(this, 1),
              error: function (res) {
                  swal("Upexpected Error", "Please contact system administrator.", "error");
              },
              failure: function (res) {
                  swal("Upexpected Error", "Please try again later.", "error");
              }
          });
      };
    }

  $(document).ready(function(){
      var viewModel = new ReservationsViewModel();
      viewModel.isEdit = "0";
      viewModel.ID = $('#txtNo').val();

      var d = new Date();
      $(".date").datepicker({
          todayBtn: "linked",
          keyboardNavigation: false,
          forceParse: false,
          calendarWeeks: true,
          autoclose: true,
          format: "dd/mm/yyyy"
      }).datepicker('setDate',moment((d.getMonth()+1)+'/'+d.getDate()+'/'+d.getFullYear()).format('DD/MM/YYYY')).datepicker('update').val('');
      $('.selectpicker').selectpicker('refresh');
      try{
        if($('#txtIsEdit').val() == "1"){
          viewModel.isEdit = $('#txtIsEdit').val();

          $.ajax({
            url: $('#callBackLoc').val()+'/get_invoice_detail',
            method: 'GET',
            contentType: "application/json; charset:utf-8",
            dataType: 'json',
            data: {'ID':$('#txtNo').val(),'pass':''},
            success: function(res){
              var res_row = res.res[0];
              var res_di = res_row.dateCreated.split("-");
              viewModel.dateCreated(moment((res_di[1])+'/'+res_di[2]+'/'+res_di[0]).format('DD/MM/YYYY'));
              viewModel.customer({id:res_row.usrID, text:res_row.usrName});
              viewModel.VoucherDescription(res_row.descrip);
              viewModel.Lines([]);
              for(var i=0; i < res.res_detail.length; i++){
                viewModel.AddLines();
                viewModel.Lines()[i].Item({id:res.res_detail[i].prdID,text:res.res_detail[i].prdName});
                viewModel.Lines()[i].description(res.res_detail[i].prdDescrip);
                viewModel.Lines()[i].amount(res.res_detail[i].amount);
                viewModel.LoadingVoucherEnable(false);
              }
            },
            error: function (res) {
                swal("Upexpected Error", "Please contact system administrator.", "error");
            },
            failure: function (res) {
                swal("Upexpected Error", "Please try again later.", "error");
            }
          });
        }

        viewModel.dateCreated(moment((d.getMonth()+1)+'/'+d.getDate()+'/'+d.getFullYear()).format('DD/MM/YYYY'));
        viewModel.VoucherDescription();
        viewModel.AddLines();
        viewModel.Lines()[0].description();
        viewModel.Lines()[0].amount("0");
        viewModel.LoadingVoucherEnable(false);
        ko.applyBindings(viewModel, document.getElementById("createExpenseView"));
      }catch(e){alert(e.message);ko.cleanNode(document.getElementById("createExpenseView"));}
    });
  });
</script>
</body>
</html>
