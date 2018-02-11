var res_txt_ddl_options = '';
var win_loc = document.getElementById("callBackLoc").value;
//////////////////////////////////////////////////
///////////     Update record     ///////////////
////////////////////////////////////////////////
function btn_update_detail(e, ID){
    swal({
        title: 'Update Product',
        html: insert_add_view(),
        showCancelButton: true,
        focusConfirm: true,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        onOpen: function() {
            get_attributes(e);
            $('#txtID').val(ID);//setting ID here, otherwise would have to pass another parameter to above functions
        },
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtName').val() == "" || $('#selUOM').val() == "" || $('#selCat').val() == "" || $('#selTyp').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
            resolve([
                $('#txtName').val(),
                $('#txtDescrip').val(),
                $('#selUOM').val(),
                $('#selCat').val(),
                $('#selTyp').val(),
                $('#txtID').val()
            ])
            })
        }
    })
    .then(function (result) {
        swal.showLoading();
            swal({
            title: "Update employee Detail",
            text: "Are you sure you want to Update this employee's detail?",
            icon: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
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
        data: {'ID':detail[5],'name':detail[0],'descrip':detail[1],'UOMID':detail[2],'catID':detail[3],'typID':detail[4]},
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
                
                swal("Product", "Updated successfully.", "success");
            }else{
                swal("Product", res.msg, "error");
            }
        }catch(e){
            swal("Product", e.message(), "error");
        }
    }
}
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
            var objUOM = $("#selUOM");
            var objCat = $("#selCat");
            var objTyp = $("#selTyp");
            $.each(res.result.UOM, function() {
                objUOM.append($("<option />").val(this.id).text(this.name));
            });
            $.each(res.result.cat, function() {
                objCat.append($("<option />").val(this.id).text(this.name));
            });
            $.each(res.result.typ, function() {
                objTyp.append($("<option />").val(this.id).text(this.name));
            });
            if(e == null){return;}
            var tableObj = $('.dataTables-grd').DataTable();
            var rowObj = $(e).closest('tr');
            $('#txtName').val(tableObj.row(rowObj).data()[0]);
            $('#txtDescrip').val(tableObj.row(rowObj).data()[1]);
            $('#selUOM').val(rowObj.find(".spnUOMID").data('id'));
            $('#selCat').val(rowObj.find(".spnCatID").data('id'));
            $('#selTyp').val(rowObj.find(".spnTypID").data('id'));
        }else{
            swal("Unexpected error", "Please contact system administrator", "error");
        }
    }catch(e){
        swal("Unexpected error", e.message, "error");
    }
}
//////////////////////////////////////////////////
///////////     Add New Record     //////////////
////////////////////////////////////////////////
function add_pre(){
    swal({
        title: 'Add New Product',
        html: insert_add_view(),
        showCancelButton: true,
        focusConfirm: true,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        onOpen: function() {
            get_attributes(null);
            $('#txtName').focus();
        },
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtName').val() == "" || $('#selUOM').val() == "" || $('#selCat').val() == "" || $('#selTyp').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
            resolve([
                $('#txtName').val(),
                $('#txtDescrip').val(),
                $('#selUOM').val(),
                $('#selCat').val(),
                $('#selTyp').val()
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
        data: {'name':detail[0],'descrip':detail[1],'UOMID':detail[2],'catID':detail[3],'typID':detail[4]},
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
            swal("Product", "Product Added Successfully!", "success");
        }else{
            swal("Product", res.msg, "error");
        }
    }catch(e){
        swal("Product", e.message, "error");
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
            title: "Disable Product!",
            text: "Are you sure you want to '"+ alertText +"' this product?",
            type: "warning",
            showLoaderOnConfirm: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "Yes, do it!",
            preConfirm: function (value) {
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
            swal("Product", "Updated successfully.", "success");
        }else{
            swal("Product", res.msg, "error");
        }
        }catch(e){
            swal("Product", e.message, "error");
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
        title: "Delete Product!",
        text: "Are you sure you want to '"+ alertText +"' this product?",
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
            swal("Product", "Deleted successfully!", "success");
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
function insert_add_view(){
    var strView = '<hr class="hr-success" />'+
    '<div class="row">'+
            '<div class="col-sm-12 form-horizontal">'+
                '<div class="form-group">'+
                    '<label class="col-sm-4 control-label" runat="server">Product<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Product Name" id="txtName" value="" class="form-control m-b " required>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" runat="server">Description</label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Description" id="txtDescrip" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label">UOM<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<select id="selUOM" class="form-control m-b">'+
                        '</select>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label">Catagory<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<select id="selCat" class="form-control m-b">'+
                        '</select>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label">Type<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<select id="selTyp" class="form-control m-b">'+
                        '</select>'+
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
    
    if(row.enable){status = ' fa-check '; button_dan_pri = ' btn-primary ';}else{status = ' fa-times ';button_dan_pri = ' btn-danger ';}
    if(row.typID == '0'){typ = 'Admin';}else if(row.typID == '1'){typ = 'Employee';}
    $('.dataTables-grd').DataTable().row.add([
        row.name,
        row.descrip,
        '<span class="spnUOMID" data-id="'+ row.UOMID +'">'+
                              row.UOMName +
                            '</span>',
        '<span class="spnCatID" data-id="'+ row.catID +'">'+
                              row.catName +
                            '</span>',
        '<span class="spnTypID" data-id="'+ row.typID +'">'+
                              row.typName +
                            '</span>',
        '<button class="btn '+ button_dan_pri +' btn-circle" type="button" onclick="btn_disable(this,'+ row.ID +');" data-id="'+ (row.enable = 1 ? 0 : 1) +'" href="javascript:void(0);"><i class="fa '+ status +'"></i>'+
            '</button>',
        '<a title="Edit" class="btn btn-primary btn-icon" id="btnEdit" onclick="btn_update_detail(this,'+ row.ID +');" href="javascript:void(0);">'+
            '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'+
            '</a>'+
            ' <a title="Delete" class="btn btn-danger btn-icon" onclick="btn_delete(this,'+ row.ID +');" data-id="1" href="javascript:void(0);" >'+
            '<i class="fa fa-trash" aria-hidden="true"></i></a>'
    ]).draw();
}