var controllerNameUsers = 'Rollers';
var swalHeader = 'Roller';
var win_loc = document.getElementById("callBackLoc").value;
//////////////////////////////////////////////////
///////////     Add New Record     //////////////
////////////////////////////////////////////////
function add_pre(){
    swal({
        title: 'Add New Rooler',
        html: insert_add_view(),
        showCancelButton: true,
        focusConfirm: true,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        onOpen: function() {
        },
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtName').val() == "" || $('#txtRatePerKg').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
            resolve([
                $('#txtName').val(),
                $('#txtAddress').val(),
                $('#txtPhone').val(),
                $('#txtRatePerKg').val(),
            ])
            })
        }
    })
    .then(function (result) {
        swal.showLoading();
        add_record(JSON.parse(JSON.stringify(result)));
    })
    .catch(swal.noop);
}
function add_record(detail){
    $.ajax({
        url: win_loc+'/add_record',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'name':detail[0],'address':detail[1],'phone':detail[2],'ratePerKg':detail[3]},
        success: onSuccess_add_record,
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_add_record(res){
    try{
        if(res.status == 200){
            add_new_row_dataTable(null, res);
            swal(swalHeader, "Record created Successfully!", "success");
        }else{
            swal(swalHeader, res.msg, "error");
        }
    }catch(e){
        swal(swalHeader, e.message, "error");
    }
}
//////////////////////////////////////////////////
///////////     Update record     ///////////////
////////////////////////////////////////////////
function btn_update_detail(e, ID){
    swal({
        title: 'Update Roller',
        html: insert_add_view(),
        showCancelButton: true,
        focusConfirm: true,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        onOpen: function() {
            var tableObj = $('.dataTables-grd').DataTable();
            var rowObj = $(e).closest('tr');
            $('#txtName').val(tableObj.row(rowObj).data()[0]);
            $('#txtAddress').val(tableObj.row(rowObj).data()[1]);
            $('#txtPhone').val(tableObj.row(rowObj).data()[2]);
            $('#txtRatePerKg').val(tableObj.row(rowObj).data()[3]);
            $('#txtID').val(ID);
        },
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtName').val() == "" || $('#txtRatePerKg').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
            resolve([
                $('#txtName').val(),
                $('#txtAddress').val(),
                $('#txtPhone').val(),
                $('#txtRatePerKg').val(),
                $('#txtID').val()
            ])
            })
        }
    })
    .then(function (result) {
        swal.showLoading();
            swal({
            title: "Update Rooler Detail",
            text: "Are you sure you want to Update this roller's detail?",
            icon: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            button: {
                text: "Yes!",
                value:true,
                visible:true,
                closeModal: false
            },defeat:true
            })
            .then((value) => {
                if(value)
                    update_detail(e, result);
            });
    })
    .catch(swal.noop);
}
function update_detail(e, detail){
    $.ajax({
        url: win_loc+'/update_detail',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'ID':detail[4],'name':detail[0],'address':detail[1],'phone':detail[2],'ratePerKg':detail[3]},
        success: onSuccess_update_detail(e),
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_update_detail(e){
    return function(res){
        try{
            if(res.status == 200){
                $('.dataTables-grd').DataTable().row($(e).closest('tr')).remove().draw();
                add_new_row_dataTable(e, res);
                
                swal(swalHeader, "Updated successfully.", "success");
            }else{
                swal(swalHeader, res.msg, "error");
            }
        }catch(e){
            swal(swalHeader, e.message(), "error");
        }
    }
}
//////////////////////////////////////////////////
///////////     Disable record     //////////////
////////////////////////////////////////////////
function btn_disable(e, ID){
    var alertText = '';
    var enable = $(e).data('id');

    if(enable){alertText = 'Enable';}else{alertText = 'Disable';}
    if (ID != "" && ID != 0){
        swal({
            title: "Disable Employee!",
            text: "Are you sure you want to '"+ alertText +"' this employee?",
            type: "warning",
            showLoaderOnConfirm: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "Yes, do it!",
            preConfirm: function (email) {
                return new Promise(function (resolve, reject) {
                    resolve(disable_record(e, enable, ID))
                })
            }
        });
    }
}
function disable_record(e, enable, ID){
    $.ajax({
        url: win_loc+'/disable_record',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'ID':ID, 'enable':parseInt(enable)},
        success: onSuccess_disable_record(e,enable),
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_disable_record(e, enable){
    return function(res){
        try{
        if(res.status == 200){
            if(enable == '1'){
                $(e).data('id','0');
                $(e).removeClass('btn-danger').addClass('btn-primary').find('i').removeClass('fa-times').addClass('fa-check');
            }else{
                $(e).data('id','1');
                $(e).removeClass('btn-primary').addClass('btn-danger').find('i').removeClass('fa-check').addClass('fa-times');
            }
            swal(swalHeader, "Updated successfully.", "success");
        }else{
            swal(swalHeader, res.msg, "error");
        }
        }catch(e){
            swal(swalHeader, e.message, "error");
        }
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
            swal(swalHeader, "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal(swalHeader, "Please try again later.", "error");
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
            swal(swalHeader, "Deleted successfully!", "success");
        }else{
            swal(swalHeader, res.msg, "error");
        }
        }catch(e){
            swal(swalHeader, e.message, "error");
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
                    '<label class="col-sm-4 control-label" runat="server">Name<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Name" id="txtName" value="" class="form-control m-b " required>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" >Address</label>'+
                    '<div class="col-sm-8">'+
                        '<Textarea type="text" placeholder="Address" id="txtAddress" value="" class="form-control m-b "></Textarea>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" runat="server">Phone</label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Phone" id="txtPhone" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" >Rate<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Rate" id="txtRatePerKg" value="" class="form-control m-b ">'+
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
    $('.dataTables-grd').DataTable().row.add([
        row.name,
        row.address,
        row.phone,
        row.ratePerKg,
        '<button class="btn '+ button_dan_pri +' btn-circle" type="button" onclick="btn_disable(this,'+ row.ID +');" data-id="'+ (row.enable = 1 ? 0 : 1) +'" href="javascript:void(0);"><i class="fa '+ status +'"></i>'+
            '</button>',
        '<a title="Edit" class="btn btn-primary btn-icon" id="btnEdit" onclick="btn_update_detail(this,'+ row.ID +');" href="javascript:void(0);">'+
            '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'+
            '</a>'+
            ' <a title="Delete" class="btn btn-danger btn-icon" onclick="btn_delete(this,'+ row.ID +');" data-id="1" href="javascript:void(0);" >'+
            '<i class="fa fa-trash" aria-hidden="true"></i></a>'
    ]).draw();
}