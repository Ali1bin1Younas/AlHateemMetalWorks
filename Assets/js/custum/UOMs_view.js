var pageName = 'UOM';
var win_loc_u = document.getElementById("callBackLoc_u").value;
//////////////////////////////////////////////////
///////////     View Record     //////////////
////////////////////////////////////////////////
function get_view_uoms(){
    $.ajax({
        url: win_loc_u+'/get_view_main',
        method: 'GET',
        success: onSuccess_get_view_uoms,
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_get_view_uoms(res){
    swal({
        title: 'Unit of Measurments',
        html: res,
        showCancelButton: false,
        showConfirmButton: false,
        focusConfirm: true,
        customClass: 'swal-wide',
        onOpen: function() {
            var tableUOM = $('.dataTables-grdUOM');
            tableUOM.DataTable({
                pageLength: 10,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    {extend: 'excel', title: 'List-pdf'},
                ]
            });
        },
        preConfirm: function () {
        }
    })
    .catch(swal.noop);
}
//////////////////////////////////////////////////
///////////     Add New Record     //////////////
////////////////////////////////////////////////
function add_pre_uom(){
    swal({
        title: 'Add New UOM',
        html: insert_add_view_uoms(),
        showCancelButton: true,
        focusConfirm: true,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        onOpen: function() {
            $('#txtName').focus();
        },
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtName').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
            resolve([
                $('#txtName').val()
            ])
            })
        }
    })
    .then(function (result) {
        swal.showLoading();
        add_record_uom(JSON.parse(JSON.stringify(result)));
    })
    .catch(swal.noop);
}
function add_record_uom(detail){
    $.ajax({
        url: win_loc_u+'/add_record',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'name':detail[0]},
        success: onSuccess_add_record_uom,
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_add_record_uom(res){
    try{
        if(res.status == 200){
            swal({title: pageName, text: "Added successfully!", type: "success",
                preConfirm: function (value) {
                    return new Promise(function (resolve, reject) {resolve(get_view_uoms())})
                }
            });
        }else{
            error_swal(res);
        }
    }catch(e){
        exception_swal(e);
    }
}
//////////////////////////////////////////////////
///////////     Update record     ///////////////
////////////////////////////////////////////////
function btn_update_detail_uom(e, ID){
    var tableObj = $('.dataTables-grdUOM').DataTable();
    var rowObj = $(e).closest('tr');
    var txtName = tableObj.row(rowObj).data()[0];
    swal({
        title: 'Update '+ pageName,
        html: insert_add_view_uoms(),
        showCancelButton: true,
        focusConfirm: true,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        onOpen: function() {
            $('#txtID').val(ID);
            $('#txtName').val(txtName);
        },
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtName').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
            resolve([
                $('#txtName').val(),
                $('#txtID').val()
            ])
            })
        }
    })
    .then(function (result) {
        swal.showLoading();
            swal({
            title: "Update "+ pageName +" Detail",
            text: "Are you sure you want to Update this "+ pageName +"'s detail?",
            icon: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            button: {
                text: "Yes!",
                value:true,
                visible:true,
                closeModal: false
            },
            })
            .then((value) => {
                if(value)
                    update_detail_uom(e, result);
            });
    })
    .catch(swal.noop);
}
function update_detail_uom(e, detail){
    $.ajax({
        url: win_loc_u+'/update_detail',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'ID':detail[1],'name':detail[0]},
        success: onSuccess_update_detail_uom(e),
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_update_detail_uom(e){
    return function(res){
        try{
            if(res.status == 200){
                swal({title: pageName, text: "Updated successfully!", type: "success",
                preConfirm: function (value) {
                    return new Promise(function (resolve, reject) {resolve(get_view_uoms())})
                }
            });
            }else{
                swal("Product", res.msg, "error");
            }
        }catch(e){
            swal("Product", e.message(), "error");
        }
    }
}
//////////////////////////////////////////////////
///////////     Disable record     //////////////
////////////////////////////////////////////////
function btn_disable_uom(e, ID){
    var alertText = '';
    var enable = $(e).data('id');

    if(enable){alertText = 'Enable';}else{alertText = 'Disable';}
    if (ID != "" && ID != 0){
        swal({
            title: "Disable "+ pageName +"!",
            text: "Are you sure you want to '"+ alertText +"' this "+ pageName +"?",
            type: "warning",
            showLoaderOnConfirm: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "Yes, do it!",
            preConfirm: function (value) {
                return new Promise(function (resolve, reject) {
                    resolve(disable_record_uom(e, enable, ID))
                })
            }
        });
    }
}
function disable_record_uom(e, enable, ID){
    $.ajax({
        url: win_loc_u+'/disable_record',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'ID':ID, 'enable':parseInt(enable)},
        success: onSuccess_disable_record_uom(e,enable),
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_disable_record_uom(e, enable){
    return function(res){
        try{
        if(res.status == 200){
            swal({title: pageName, text: "Updated successfully!", type: "success",
                preConfirm: function (value) {
                    return new Promise(function (resolve, reject) {resolve(get_view_uoms())})
                }
            });
        }else{
            error_swal(res);
        }
        }catch(e){
            exception_swal(e);
        }
    }
}
//////////////////////////////////////////////////
///////////     Delete record     ///////////////
////////////////////////////////////////////////
function btn_delete_uom(e, ID){
    var alertText = '';
    var deleted = $(e).data('id');

    if(deleted){alertText = 'Delete';}else{alertText = 'Restore';}
    if (ID != "" && ID != 0){
        swal({
        title: "Delete "+ pageName +"!",
        text: "Are you sure you want to '"+ alertText +"' this "+ pageName +"?",
        type: "warning",
        showLoaderOnConfirm: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: "Yes, do it!",
        preConfirm: function (email) {
                return new Promise(function (resolve, reject) {
                    resolve(delete_record_uom(e, deleted, ID))
                })
            }
        });
    }
}
function delete_record_uom(e, deleted, ID){
    $.ajax({
        url: win_loc_u+'/delete_record',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'ID':ID,'enable':parseInt(deleted==1?0:1),'deleted':deleted},
        success: onSuccess_delete_record_uom(e, deleted),
        error: function (res) {
            error_swal(res);
        },
        failure: function (res) {
            error_swal(res);
        }
    });
}
function onSuccess_delete_record_uom(e, deleted){
    return function(res){
        try{
        if(res.status == '200'){
            swal({title: pageName, text: "Updated successfully!", type: "success",
                preConfirm: function (value) {
                    return new Promise(function (resolve, reject) {resolve(get_view_uoms())})
                }
            });
        }else{
            swal("Product", res.msg, "error");
        }
        }catch(e){
            swal("Product", e.message, "error");
        }
    }
}
/////////////////////////////////////////////////
///////////     Helping Methods     ////////////
///////////////////////////////////////////////
function insert_add_view_uoms(){
    var strView = '<hr class="hr-success" />'+
        '<div class="row">'+
            '<div class="col-sm-12 form-horizontal">'+
                '<div class="form-group">'+
                    '<label class="col-sm-4 control-label" runat="server">UOM Name<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="UOM" id="txtName" value="" class="form-control m-b " required>'+
                    '</div>'+
                    '<input type="hidden" id="txtID" />'+
                '</div>'+
            '</div>'+
        '</div>';
        return strView;
}
function error_swal(res){
    swal({title: pageName, text: res.msg, type: "error",
        preConfirm: function (value) {
            return new Promise(function (resolve, reject) {resolve(get_view_uoms())})
        }
    });
}
function exception_swal(e){
    swal({title: pageName, text: e.message, type: "error",
        preConfirm: function (value) {
            return new Promise(function (resolve, reject) {resolve(get_view_uoms())})
        }
    });
}