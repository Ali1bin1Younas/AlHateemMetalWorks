<?php 
$this->load->view('common/header');
//$total=count($all_user);
?>
<link href="<?php echo base_url(); ?>Assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
 <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Users
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
                <a class="btn btn-primary pad" id="btnAddUser" onclick="add_user_pre();" href="javascript:void(0);"><i class="fa fa-plus" aria-hidden="true"></i>Add New</a>
                </div>
                </div>
                  <div class="table-responsive">
                    <table id="tblUsers" class="table table-bordered table-striped icon_space table-bordered table-hover dataTables-grdUsers">
                      <thead>
                        <tr>
                          <th>Full Name</th>
                          <th>Username</th>
                          <th>Mobile</th>
                          <th>Tel.</th>
                          <th>Type</th>
                          <th>Enable</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="grdUsers">
                        <?php foreach($user_data as $user){?>
                        <tr id="trUsr<?php echo $user['usrID'];?>" class="active">
                          <td id="tdUsrFullName<?php echo $user['usrID'];?>">
                            <?php echo $user['usrFullName'];?>
                          </td>
                          <td id="tdUsrName<?php echo $user['usrID'];?>">
                            <?php echo $user['usrName'];?>
                          </td>
                          <td id="tdUsrMobile<?php echo $user['usrID'];?>">
                            <?php echo $user['usrMobile'];?>
                          </td>
                          <td id="tdUsrTell<?php echo $user['usrID'];?>">
                            <?php echo $user['usrTell'];?>
                          </td>
                          <td>
                            <?php 
                              if($user['usrTypID'] == 0){echo "Admin";}
                              elseif($user['usrTypID'] == 1){echo "Employee";}
                              elseif($user['usrTypID'] == 2){echo "Viewer";} 
                              elseif($user['usrTypID'] == 3){echo "Editor";}
                            ?>
                          </td>
                          <td>
                            <?php if($user['usrEnable']){?>
                              <button class="btn btn-primary btn-circle" type="button" onclick="btn_disable_user(this,<?php echo $user['usrID'];?>);" data-id="0" href="javascript:void(0);"><i class="fa fa-check"></i>
                              </button>
                              <?php }else{?>
                              <button class="btn btn-danger btn-circle" type="button" onclick="btn_disable_user(this,<?php echo $user['usrID'];?>);" data-id="1" href="javascript:void(0);"><i class="fa fa-times"></i>
                              </button>
                            <?php } ?>
                          </td>
                          <td>
                            <a title="Edit" class="btn btn-primary btn-icon" id="btnEdit<?php echo $user['usrID'];?>" onclick="btn_edit_user_view_toggle(this,<?php echo $user['usrID'];?>);" href="javascript:void(0);" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <?php if($user['usrDeleted']){?>
                              <a title="Retore" class="btn btn-primary btn-icon" id="btnRestore<?php echo $user['usrID'];?>" onclick="btn_delete_user(this,<?php echo $user['usrID'];?>);" data-id="0" href="javascript:void(0);" ><i class="fa fa-undo" aria-hidden="true"></i></a>
                              </button>
                              <?php }else{?>
                              <a title="Delete" class="btn btn-danger btn-icon" id="btnDelete<?php echo $user['usrID'];?>" onclick="btn_delete_user(this,<?php echo $user['usrID'];?>);" data-id="1" href="javascript:void(0);" ><i class="fa fa-trash" aria-hidden="true"></i></a>
                              </button>
                            <?php } ?>
                            <a title="Change Password" tooltip="Change password" class="btn btn-warning btn-icon" id="btnChangePass<?php echo $user['usrID'];?>" onclick="btn_change_pass(this,<?php echo $user['usrID'];?>);" href="javascript:void(0);" ><i class="fa fa-key" aria-hidden="true"></i></a>
                          </td>       
                        </tr>
                        <!--<tr id="trUsr<?php echo $user['usrID'];?>Edit" style="display:none;">
                          <td colspan="15">
                            <div class="row">
                              <div class="col-sm-12 text-center">
                                <div class="alert alert-danger alert-dismissable" id="divError<?php echo $user['usrID'];?>" style="display:none;" onclick="this.style.display = 'none';">
                                  <button class="close" aria-hidden="true" id="close" type="button">×</button>
                                  <span id="spnError<?php echo $user['usrID'];?>"></span>
                                </div>
                              </div>
                              <div class="col-md-12 form-horizontal">
                                <div class="form-group">

                                  <label class="col-sm-2 control-label" runat="server">Full Name*</label>
                                  <div class="col-sm-10">
                                      <input type="text" placeholder="Full Name" id="usrFullName<?php echo $user['usrID'];?>" value="<?php echo $user['usrFullName'];?>" class="form-control m-b ">
                                  </div>
                                  <label class="col-sm-2 control-label" runat="server">Email</label>
                                  <div class="col-sm-10">
                                      <input type="email" placeholder="Email" id="usrEmail<?php echo $user['usrID'];?>" value="<?php echo $user['usrEmail'];?>" class="form-control m-b ">
                                  </div>

                                  <label class="col-sm-2 control-label" runat="server" >Mobile*</label>
                                  <div class="col-sm-10">
                                      <input type="text" placeholder="Mobile" id="usrMobile<?php echo $user['usrID'];?>" value="<?php echo $user['usrMobile'];?>" class="form-control m-b ">
                                  </div>

                                  <label class="col-sm-2 control-label" runat="server" >Tel.</label>
                                  <div class="col-sm-10">
                                      <input type="text" placeholder="Tel" id="usrTell<?php echo $user['usrID'];?>" value="<?php echo $user['usrTell'];?>" class="form-control m-b ">
                                  </div>
                                  
                                  <label class="col-sm-2 control-label" runat="server" >Address</label>
                                  <div class="col-sm-10">
                                      <textarea type="text" placeholder="Tel" id="usrAddress<?php echo $user['usrID'];?>" class="form-control m-b "><?php echo $user['usrAddress'];?></textarea>
                                  </div>

                                  <label class="col-sm-2 control-label">DOB*</label>
                                  <div class="col-sm-10">
                                    <div id="data<?php echo $user['usrID'];?>" class="input-group date">
                                      <?php $dob = date_create($user['usrDOB']);?>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input value="<?php echo date_format($dob,"d/m/Y");?>" id="usrDOB<?php echo $user['usrID'];?>" readonly="readonly" type="text" class="form-control">
                                    </div>
                                  </div>

                                </div>
                                <div class="form-group">
                                  <div class="col-sm-2 col-sm-offset-2">
                                      <input type="button" id="btnUpdate<?php echo $user['usrID'];?>" onclick="btn_update_user_detail(this,<?php echo $user['usrID'];?>);" class="btn btn-primary" value="Save changes"/>
                                  </div>
                                  <div class="col-sm-2">
                                      <input type="button" id="btnCancel<?php echo $user['usrID'];?>" onclick="btn_edit_user_view_hide(this);" class="btn btn-primary" value="Cancel"/>
                                  </div>
                                </div>
                              </div>
                          </div>
                          </td>
                        </tr>-->
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
  </div>
 <!-- /.content-wrapper -->

<?php  $this->load->view('common/footer');  ?>

<!--Page Scripts -->
<script src="<?php echo base_url(); ?>assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<!--<script src="<?php echo base_url(); ?>Assets/js/plugins/sweetalert/sweetalert2.0.min.js" defer="defer"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.5/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/custum/users_view.js"></script>
<!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
           $('.dataTables-grdUsers').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend: 'copy'},
                    //{extend: 'csv'},
                    {extend: 'excel', title: 'Users-List-pdf'},
                    {extend: 'pdf', title: 'Users-List-pdf'},
                    {extend: 'print',
                     customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    }
                    }
                ]
            });

        //$('#tblUsers').DataTable('.dataTables-grdUsers');
        });
    </script>
</body>
</html>