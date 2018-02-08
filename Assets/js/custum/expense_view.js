var pageNameV = 'Create Voucher';
var controllerNameV = 'Sale';
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
function expenses_edit(id){
    $.ajax({
        url: win_loc+'/set_edit_session',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'id':id,'pass':''},
        success: function(res){
            if(res.id != null && res.id != '' && res.id != 0)
                window.location.href = win_loc+'/expenses_edit';
            else
            swal("Upexpected Error", "Please contact system administrator.", "error");
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