<style>
  .add-voucher{
    font-size:12px;
  }
</style>
<link href="<?php echo base_url(); ?>Assets/css/plugins/bootstrap-select/bootstrap-select.css" rel="stylesheet">
<!-- Content Wrapper. Contains page content -->
<div class="fadeInRight animated">
  <hr class="hr-success" />
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content add-voucher">
      <div class="row">
        <div class="col-xs-12 rem">
          <div class="box">
            <div class="box-body">
              <div class="row">
                <div class="col-sm-12">
                  <table>
                    <tbody>
                      <tr>
                        <td>
                          <div class="form-group">
                            <label>Issue date</label>
                            <div class="input-group date">
                              <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="txtDateTimeCreated" style="width: 100px; margin-bottom: 0px; text-align: center" readonly="readonly" type="text" class="form-control">
                            </div>
                        </td>
                        <td style="padding-left: 10px">
                          <div class="form-group">
                            <label>Delivery Date</label>
                            <div class="input-group date">
                              <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="txtDateTimeDelivery" style="width: 100px; margin-bottom: 0px; text-align: center" readonly="readonly" type="text" class="form-control">
                            </div>
                            </div>
                        </td>
                        <td style="padding-left: 10px">
                          <div class="form-group">
                            <label>Reference</label>
                            <div class="input-group" style="margin-bottom: 0px">
                              <span class="input-group-addon">#</span>
                              <input class="form-control input-sm" style="width: 80px; text-align: center" placeholder="Automatic" type="text">
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="form-group">
                    <div class="row-fluid text-center">
                      <select class="selectpicker" id="ddlUsers" data-show-subtext="true" data-live-search="true">
                        <option>abc
                        </option>
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
  </section>
    <!-- /.content -->
</div>
 <!-- /.content-wrapper -->
</div>