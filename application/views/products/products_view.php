<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url(); ?>Assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
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
                <a class="btn btn-primary pad" onclick="get_view_uoms();" href="javascript:void(0);"><i class="fa fa-plus" aria-hidden="true"></i>UOMs</a>
                <a class="btn btn-primary pad" onclick="get_view_cat();" href="javascript:void(0);"><i class="fa fa-plus" aria-hidden="true"></i>Catagories</a>
                <a class="btn btn-primary pad" onclick="get_view_typ();" href="javascript:void(0);"><i class="fa fa-plus" aria-hidden="true"></i>Types</a>
                <a class="btn btn-primary pad" onclick="add_pre();" href="javascript:void(0);"><i class="fa fa-plus" aria-hidden="true"></i>Add Product</a>
                </div>
                </div>
                  <div class="table-responsive">
                    <table id="grd" class="table table-bordered table-striped icon_space table-bordered table-hover dataTables-grd">
                      <thead>
                        <tr>
                          <th>Product Name</th>
                          <th>Description</th>
                          <th>UOM</th>
                          <th>Category</th>
                          <th>Type</th>
                          <th>Enable</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="grd">
                        <?php foreach($row_data as $row){?>
                        <tr>
                          <td><?php echo $row['name'];?></td>
                          <td><?php echo $row['descrip'];?></td>
                          <td>
                            <span class="spnUOMID" data-id="<?php echo $row['UOMID']; ?>">
                              <?php echo $row['UOMName'];?>
                            </span>
                          </td>
                          <td>
                            <span class="spnCatID" data-id="<?php echo $row['catID']; ?>">
                              <?php echo $row['catName'];?>
                            </span>
                            </td>
                          <td>
                            <span class="spnTypID" data-id="<?php echo $row['typID']; ?>">
                              <?php echo $row['typName'];?>
                            </span>
                          </td>
                          <td>
                            <?php if($row['enable']){?>
                              <button class="btn btn-primary btn-circle" type="button" onclick="btn_disable(this,<?php echo $row['ID'];?>);" data-id="0" href="javascript:void(0);"><i class="fa fa-check"></i>
                              </button>
                              <?php }else{?>
                              <button class="btn btn-danger btn-circle" type="button" onclick="btn_disable(this,<?php echo $row['ID'];?>);" data-id="1" href="javascript:void(0);"><i class="fa fa-times"></i>
                              </button>
                            <?php } ?>
                          </td>
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
    <!-- /.content -->
    <input type="hidden" id="callBackLoc" value="<?php echo base_url('Products');?>"/>
    <input type="hidden" id="callBackLoc_c" value="<?php echo base_url('Catagories');?>"/>
    <input type="hidden" id="callBackLoc_t" value="<?php echo base_url('Types');?>"/>
    <input type="hidden" id="callBackLoc_u" value="<?php echo base_url('UOMs');?>"/>
  </div>
 <!-- /.content-wrapper -->
<?php  $this->load->view('common/footer');  ?>
<!--Page Scripts -->
<script src="<?php echo base_url(); ?>Assets/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/plugins/sweetalert/sweetalert2.0.min.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/custum/products_view.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/custum/UOMs_view.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/custum/catagories_view.js"></script>
<script src="<?php echo base_url(); ?>Assets/js/custum/types_view.js"></script>
<!-- Page-Level Scripts -->
  <script>
    $(document).ready(function(){
      var table = $('.dataTables-grd');
      table.DataTable({
          "columnDefs": [
              //{ "targets": [0], "visible": false, "searchable": false}
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