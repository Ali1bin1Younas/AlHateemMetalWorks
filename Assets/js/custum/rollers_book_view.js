var pageNameV = 'Roolers Book';
var controllerNameV = 'Roolers Book';
var win_loc = document.getElementById("callBackLoc").value;
//////////////////////////////////////////////////
///////////     View Record     //////////////
////////////////////////////////////////////////
function onSuccess_get_view(res){
    $('.page-contents').html(res);
    var viewModel = new ReservationsViewModel();

    $(".date").datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: "dd/mm/yyyy"
    }).datepicker('setDate', new Date()).datepicker('update').val('');
    $('.selectpicker').selectpicker('refresh');
    try{
        // Overall viewmodel for the popup screen, along with initial state
        viewModelInit = true;
        viewModel.issueDate(new Date());
        viewModel.referenceNo("");
        viewModel.VoucherDescription();
        viewModel.discount(false);
        viewModel.discountType("Percentage");

        viewModel.AddLines();
        viewModel.Lines()[0].description();
        viewModel.Lines()[0].amount("0");
        
        ko.applyBindings(viewModel, document.getElementById("createVoucherView"));
    }catch(e){alert(e.message);ko.cleanNode(document.getElementById("createVoucherView"));}
}
function edit_sale(id){
    $.ajax({
        url: win_loc+'/set_edit_session',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'id':id,'pass':''},
        success: function(res){
            if(res.id != null && res.id != '' && res.id != 0)
                window.location.href = win_loc+'/sale_edit';
        },
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
//////////////////////////////////////////////////
///////////     Update record     ///////////////
////////////////////////////////////////////////
function btn_update_detail(e, ID){
    swal({
        title: 'Update Employee',
        html: insert_add_view(),
        showCancelButton: true,
        focusConfirm: true,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        onOpen: function() {
            get_attributes(e);
            $("#data").datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "dd/mm/yyyy"
            });
            document.getElementById("lblPass").style.display = "none";
            document.getElementById("divPass").style.display = "none";
            var tableObj = $('.dataTables-grd').DataTable();
            var rowObj = $(e).closest('tr');
            $('#txtFullName').val(tableObj.row(rowObj).data()[0]);
            $('#txtName').val(tableObj.row(rowObj).data()[1]);
            $('#txtEmail').val(tableObj.row(rowObj).data()[4]);
            $('#txtMobile').val(tableObj.row(rowObj).data()[5]);
            $('#txtTell').val(tableObj.row(rowObj).data()[6]);
            $('#txtAddress').val(tableObj.row(rowObj).data()[2]);
            //types dropdown is bieng filled in get_attributes(e) method
            var DOB = moment(tableObj.row(rowObj).data()[3]).format("DD/MM/YYYY");
            $('#txtDOB').val(DOB);
            $('#txtID').val(ID);

            $('#data').datepicker('setDate', DOB);
            $('#data').datepicker('update');
            $('#data').val('');
        },
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtName').val() == "" || $('#txtFullName').val() == "" || $('#selTypID').val() == "" || $('#txtDOB').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
            resolve([
                $('#txtName').val(),
                $('#txtPass').val(),
                $('#txtFullName').val(),
                $('#txtEmail').val(),
                $('#txtMobile').val(),
                $('#txtTell').val(),
                $('#txtAddress').val(),
                $('#selUsrTyp').val(),
                $('#txtDOB').val(),
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
    var strDOB = detail[8];
    var DOB = strDOB.toString().split('/');
    $.ajax({
        url: win_loc+'/update_detail',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'ID':detail[9],'name':detail[0],'fullName':detail[2],'email':detail[3],'mobile':detail[4],'tell':detail[5],'address':detail[6],'typID':detail[7],'DOB':moment(DOB[1]+'/'+DOB[0]+'/'+DOB[2]).format('YYYY/MM/DD')},
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
                
                swal("User Profile", "Updated successfully.", "success");
            }else{
                swal("User Profile", res.msg, "error");
            }
        }catch(e){
            swal("User Profile", e.message(), "error");
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
            swal("User Profile", "Updated successfully.", "success");
        }else{
            swal("User Profile", res.msg, "error");
        }
        }catch(e){
            swal("User Profile", e.message, "error");
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