<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url(); ?>Assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/bootstrap-select/bootstrap-select.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/select2/select2.min.css" rel="stylesheet">

 <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $pageHeading; ?>
      </h1>
    </section>
    <?php echo $this->session->flashdata('msg'); ?>
    <!-- Main content -->
    <div class="fadeInRight animated">
      <section class="content">
        <div class="row">
          <div class="col-xs-12 rem">
            <div class="box">
                <div class="box-header">
                  <!-- <h3 class="box-title">Data Table With Full Features</h3>-->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                  <div class="col-lg-12 text-right form-group">
                  <a class="btn btn-primary pad"  href="voucherCreate"><i class="fa fa-plus" aria-hidden="true"></i>Create voucher</a>
                  </div>
                  </div>
                    <div class="table-responsive">
                      <table id="grd" class="table table-bordered table-striped icon_space table-bordered table-hover dataTables-grd">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>DateTime Created</th>
                            <th>Type</th>
                            <th>Discount</th>
                            <th>Refernce No</th>
                            <th>Vendor</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="grdUsers">
                          <?php foreach($row_data as $row){?>
                          <tr>
                            <td><?php echo $row['voucherNo'];?></td>
                            <td><?php echo $row['dateTimeCreated'];?></td>
                            <td>
                                <span class="spnVTypID" data-id="<?php echo $row['typID']; ?>">
                                  <?php echo $row['typName']; ?>
                                </span>
                            </td>
                            <td><?php echo $row['discount'];?></td>
                            <td><?php echo $row['referenceNo'];?></td>
                            <td><?php echo $row['usrName'];?></td>
                            <td>
                              <a title="Edit" class="btn btn-primary btn-icon" id="btnEdit" onclick="btn_update_detail(this,<?php echo $row['ID'];?>);" href="javascript:void(0);" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                              <?php if($row['deleted']){?>
                                <a title="Retore" class="btn btn-primary btn-icon" id="btnRestore" onclick="btn_delete(this,<?php echo $row['ID'];?>);" data-id="0" href="javascript:void(0);" ><i class="fa fa-undo" aria-hidden="true"></i></a>
                                </button>
                                <?php }else{?>
                                <a title="Delete" class="btn btn-danger btn-icon" id="btnDelete" onclick="btn_delete(this,<?php echo $row['ID'];?>);" data-id="1" href="javascript:void(0);" ><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </button>
                              <?php } ?>
                            </td>       
                          </tr>
                          <?php } ?>
                        </tbody>
                      
                      </table>
                    </div>
                </div><!-- /.box-body -->
            </div>
          </div> 
          </div>
      </section>
    </div>
    <!-- /.content -->
  </div>
 <!-- /.content-wrapper -->
<div>
<?php  $this->load->view('common/footer');  ?>

<!--Page Scripts -->
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/sweetalert/sweetalert2.0.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-select/bootstrap-select.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/globalize.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/knockoutJS/knockout-3.4.2.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/knockoutJS/knockout-sortable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/custum/vouchers_view.js"></script>
<!-- Page-Level Scripts -->
<script>
  $(document).ready(function(){
    var table = $('.dataTables-grd');
    table.DataTable({
        "columnDefs": [
            //{ "targets": [2,3,4], "visible": false, "searchable": false}
        ],
        "bAutoWidth": false,
        pageLength: 10,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'copy'},
            //{extend: 'csv'},
            {extend: 'excel', title: 'List-pdf'},
            {extend: 'pdf', title: 'List-pdf'},
            {extend: 'print',
              customize: function (win){
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');
                $(win.document.body).find('dataTables-grd').addClass('compact').css('font-size', 'inherit');
            }
            }
        ]
    });
  });

</script>
</body>
</html>