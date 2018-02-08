var pageNameV = 'Create Purchase';
var controllerNameV = 'Purchase';
var win_loc = document.getElementById("callBackLoc").value;
//////////////////////////////////////////////////
///////////     Add New Record     //////////////
////////////////////////////////////////////////
function onSuccess_add_record(viewModel, isNew){
    return function(res){
        try{
            if(res.status == 200){
                swal("Purchase", "Record updated!", "success");
                if(isNew){
                    var d = new Date();
                    viewModel.issueDate(moment((d.getMonth()+1)+'/'+d.getDate()+'/'+d.getFullYear()).format('DD/MM/YYYY'));
                    viewModel.referenceNo("");
                    viewModel.purchaseNo = (parseInt($('#txtPurchaseNo').val()) + 1);
                    viewModel.VoucherDescription("");
                    viewModel.discount(false);
                    viewModel.discountType("Percentage");
                    viewModel.Lines.clear();

                    viewModel.AddLines();
                    viewModel.Lines()[0].description("");
                    viewModel.Lines()[0].amount("0");
                }else{
                    $(location).attr('href', $('#callBackLoc').val());
                }
            }else{
                swal("Purchase", "Unexpected error, please contact system administrator.", "error");
            }
        }catch(e){
            swal("Purchase", e.message, "error");
        }
    }
}
//////////////////////////////////////////////////
///////////     get & fill Attributes    ////////
////////////////////////////////////////////////
function get_attributes(e){
    swal.showLoading();
    $.ajax({
        url: win_loc+'/get_attributes',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        success: onSuccess_get_attributes(e),
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_get_attributes(e){
    return function(res){
        attribute_filling(e, res);
    }
}
function attribute_filling(e, res){
    try{
        swal.hideLoading();
        if(res.status == 200){
            var obj = $("#selUsrTyp");
            $.each(res.result.usersTypes, function() {
                obj.append($("<option />").val(this.id).text(this.name));
            });
            if(e == null){return;}
            var rowObj = $(e).closest('tr');
            obj.val(rowObj.find(".spnUsrTypID").data('id'));
        }else{
            swal("Unexpected error", "Please contact system administrator", "error");
        }
    }catch(e){
        swal("Unexpected error", e.message, "error");
    }
}
//////////////////////////////////////////////////
///////////     Delete record     ///////////////
////////////////////////////////////////////////
function btn_delete(e, ID){
    var alertText = '';
    var deleted = $(e).data('id');

    if(deleted){alertText = 'Delete';}else{alertText = 'Restore';}
    if (ID != "" && ID != 0){
        swal({
        title: "Delete Employee!",
        text: "Are you sure you want to '"+ alertText +"' this employee?",
        type: "warning",
        showLoaderOnConfirm: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: "Yes, do it!",
        preConfirm: function (email) {
                return new Promise(function (resolve, reject) {
                    resolve(delete_record(e, deleted, ID))
                })
            }
        });
    }
}
function delete_record(e, deleted, ID){
    $.ajax({
        url: win_loc+'/delete_record',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'ID':ID,'enable':parseInt(deleted==1?0:1),'deleted':deleted},
        success: onSuccess_delete_record(e, deleted),
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_delete_record(e, deleted){
    return function(res){
        try{
        if(res.status == '200'){
            if(deleted == '1'){
                $(e).data('id','0');
                $('.dataTables-grd').DataTable().row($(e).closest('tr')).remove().draw();
            }else{
                $(e).data('id','1');
            }
            swal("User Profile", "Deleted successfully!", "success");
        }else{
            swal("User Profile", res.msg, "error");
        }
        }catch(e){
            swal("User Profile", e.message, "error");
        }
    }
}
/////////////////////////////////////////////////
///////////     Helping Methods     ////////////
///////////////////////////////////////////////
function insert_add_view(){
    var strView = '<div class="row">'+
            '<div class="col-sm-12 form-horizontal">'+
                '<div class="form-group">'+
                    '<label class="col-sm-4 control-label" runat="server">Username<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Username" id="txtName" value="" class="form-control m-b " required>'+
                    '</div>'+
                    '<label id="lblPass" class="col-sm-4 control-label" runat="server">Password<font color="red">*</font></label>'+
                    '<div class="col-sm-8" id="divPass">'+
                        '<input type="password" placeholder="Password" id="txtPass" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" runat="server">Full Name<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Full Name" id="txtFullName" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" >Email</label>'+
                    '<div class="col-sm-8">'+
                        '<input type="email" placeholder="Email" id="txtEmail" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" >Mobile</label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Mobile" id="txtMobile" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label">Tel.</label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Tel." id="txtTell" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" >Address</label>'+
                    '<div class="col-sm-8">'+
                        '<Textarea type="text" placeholder="Address" id="txtAddress" value="" class="form-control m-b "></Textarea>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label">User Type<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<select id="selUsrTyp" class="form-control m-b">'+
                            '<option value="">-Select Type-</option>'+
                        '</select>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label">DOB<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<div id="data" class="input-group date">'+
                            '<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input value="" id="txtDOB" readonly="readonly" type="text" class="form-control">'+
                        '</div>'+
                    '</div>'+
                    '<input type="hidden" id="txtID" />'+
                '</div>'+
            '</div>'+
        '</div>';
        return strView;
}
function add_new_row_dataTable(e, res){
    var row = res.result[0];
    var typ = 0;
    var status = '';
    var button_dan_pri = '';
    
    if(row.enable){status = 'fa-check"'; button_dan_pri = 'btn-primary';}else{status = 'fa-times';button_dan_pri = 'btn-danger';}
    var DOB = row.DOB.split("-");
    DOB = DOB[2] +'/'+ DOB[1] +'/'+ DOB[0];
    $('.dataTables-grd').DataTable().row.add([
        row.fullName,
        row.name,
        row.address,
        row.DOB,
        row.email,
        row.mobile,
        row.tell,
        '<span class="tdTypID" data-id="'+ row.typID +'">'+ row.typName +'</span>',
        '<button class="btn '+ button_dan_pri +' btn-circle" type="button" onclick="btn_disable(this,'+ row.ID +');" data-id="'+ (row.enable = 1 ? 0 : 1) +'" href="javascript:void(0);"><i class="fa '+ status +'"></i>'+
            '</button>',
        '<a title="Edit" class="btn btn-primary btn-icon" id="btnEdit" onclick="btn_update_detail(this,'+ row.ID +');" href="javascript:void(0);">'+
            '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'+
            '</a>'+
            ' <a title="Delete" class="btn btn-danger btn-icon" onclick="btn_delete(this,'+ row.ID +');" data-id="1" href="javascript:void(0);" >'+
            '<i class="fa fa-trash" aria-hidden="true"></i></a>'+
            ' <a title="Change Password" class="btn btn-warning btn-icon" id="btnChangePass'+ row.ID +'" onclick="btn_change_pass(this,'+ row.ID +');" href="javascript:void(0);" >'+
            '<i class="fa fa-key" aria-hidden="true"></i></a>'
    ]).draw();
}