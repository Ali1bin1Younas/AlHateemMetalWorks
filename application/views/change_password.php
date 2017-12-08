
<?php 
$this->load->view('commons/header');
//$this->load->view('commons/sidebar');

?>




 <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User
        <small>Add User</small>
      </h1>
      
    </section>
    
    <!-- Main content -->
    <section class="content">
    	<div class="col-md-6">
            <!-- Small boxes (Stat box) -->
            <div class="row">
            <!-- general form elements -->
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">User Form</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php echo $this->session->flashdata('message')?>
                    <form action="<?php echo base_url(); ?><?php echo $this->config->item('dashboard_path'); ?>submit_change_password"  id="frm" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                      	<div class="row">
                            <div class="col-lg-6">
                            	<div class="form-group">
                          <label for="section">Old Password</label>
                          <input type="password" value="<?php echo set_value('old_password'); ?>"  name="old_password" id="old_password" class="form-control" placeholder="Old Password">				 <div class="text-red"><?php echo form_error('old_password'); ?></div>
                        </div>
                            </div>
                        	<div class="col-lg-6">
                            <div class="form-group">
                          <label for="section">New Password</label>
                          <input type="password" value="<?php echo set_value('user_name'); ?>"  name="new_password" id="new_password" class="form-control" placeholder="New Password">
                           <div class="text-red"><?php echo form_error('new_password'); ?></div>
                        </div>
                           
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-lg-6">
                        <div class="form-group">
                          <label for="section">Confirm Password</label>
                          <input type="password" value="<?php echo set_value('c_password'); ?>"  name="c_password" id="c_password" class="form-control" placeholder="Confirm Password">
                       <div class="text-red"><?php echo form_error('c_password'); ?></div>
                        </div>
                        </div>
                        
                        
                   </div>
               
                        
                      </div><!-- /.box-body -->
            
                      <div class="box-footer text-right">
                        <button type="submit" id="submit" class="btn btn-primary">Save</button>
                      </div>
                    </form>
                  </div>
                  <!-- /.box -->
            </div><!-- /.row -->   
      	</div> 
    </section>
    <!-- /.content -->
    </div>
 <!-- /.content-wrapper -->
<?php $this->load->view('commons/footer'); ?>