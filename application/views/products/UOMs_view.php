<!-- Content Wrapper. Contains page content -->
<hr class="hr-success" />
  <div class="content-wrapper">
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
                <a class="btn btn-primary pad" id="btnAdd" onclick="add_pre_uom();" href="javascript:void(0);"><i class="fa fa-plus" aria-hidden="true"></i>UOM</a>
                </div>
                </div>
                  <div class="table-responsive">
                    <table id="grdUOM" class="table table-bordered table-striped icon_space table-bordered table-hover dataTables-grdUOM">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Enable</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="grdBodyUOM">
                        <?php foreach($row_data as $row){?>
                        <tr>
                          <td><?php echo $row['name'];?></td>
                          <td>
                            <?php if($row['enable']){?>
                              <a class="btn btn-primary btn-circle" onclick="btn_disable_uom(this,<?php echo $row['ID'];?>);" data-id="0" href="javascript:void(0);" ><i class="fa fa-check" aria-hidden="true"></i></a>
                              </button>
                              <?php }else{?>
                              <a class="btn btn-danger btn-circle" onclick="btn_disable_uom(this,<?php echo $row['ID'];?>);" data-id="1" href="javascript:void(0);" ><i class="fa fa-times" aria-hidden="true"></i></a>
                              </button>
                            <?php } ?>
                          </td>
                          <td>
                            <a title="Edit" class="btn btn-primary btn-icon" id="btnEdit" onclick="btn_update_detail_uom(this,<?php echo $row['ID'];?>);" href="javascript:void(0);" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <?php if($row['deleted']){?>
                              <a title="Retore" class="btn btn-primary btn-icon" id="btnRestore" onclick="btn_delete_uom(this,<?php echo $row['ID'];?>);" data-id="0" href="javascript:void(0);" ><i class="fa fa-undo" aria-hidden="true"></i></a>
                              </button>
                              <?php }else{?>
                              <a title="Delete" class="btn btn-danger btn-icon" id="btnDelete" onclick="btn_delete_uom(this,<?php echo $row['ID'];?>);" data-id="1" href="javascript:void(0);" ><i class="fa fa-trash" aria-hidden="true"></i></a>
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
  </div>
 <!-- /.content-wrapper -->