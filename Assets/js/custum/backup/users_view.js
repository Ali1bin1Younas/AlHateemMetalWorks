///////////////////////////////////////////////////
///////////     Toggle functions     /////////////
/////////////////////////////////////////////////
function btn_edit_user_view_toggle(e, usrID){
    $('#data' + usrID).datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'dd/mm/yyyy'
    });

    $(e).closest("tr").next().slideToggle();

    //var container = document.getElementById('btnUpdate'+usrID);
    document.getElementById('btnUpdate'+usrID).scrollIntoView()({block: 'end',  behaviour: 'smooth'});
    //$("html, body").animate({ scrollTop: $('#btnUpdate'+usrID).position().top }, 1000);
}
function btn_edit_user_view_hide(e){
    $(e).closest("tr").slideToggle();
}
//////////////////////////////////////////////////
///////////     Update User Detail     //////////
////////////////////////////////////////////////
function btn_update_user_detail(e, usrID){
    document.getElementById("divError"+usrID).style.display = "none";

    if ($('#usrFullName'+usrID).val() != "" && $('#usrEmail'+usrID).val() != "" && $('#usrMobile'+usrID).val() != "" && $('#usrDOB'+usrID).val() != "") {
        usrID;
        swal({
        title: "Update Detail",
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
                update_user_profile(usrID);
        });
    }
}
function update_user_profile(usrID){
    var strUsrDOB = $('#usrDOB'+usrID).val();
    var usrDOB = strUsrDOB.toString().split('/');
    $.ajax({
        url: 'users/update_user_profile',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'usrID':usrID, 'usrFullName':$('#usrFullName'+usrID).val(), 'usrEmail':$('#usrEmail'+usrID).val(), 'usrMobile': $('#usrMobile'+usrID).val(), 'usrTell': $('#usrTell'+usrID).val(), 'usrAddress': $('#usrAddress'+usrID).val(), 'usrDOB': moment(usrDOB[1]+'/'+usrDOB[0]+'/'+usrDOB[2]).format('YYYY/MM/DD')},
        success: onSuccess_update_user_profile,
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_update_user_profile(res){
    try{
        if(res.status == 200){
            var row = res.result[0];
            var usrID = row.usrID;
            $('#tdUsrFullName'+usrID).text(row.usrFullName);
            $('#tdUsrEmail'+usrID).text(row.usrEmail);
            $('#tdUsrMobile'+usrID).text(row.usrMobile);
            $('#tdUsrTell'+usrID).text(row.usrTell);
            swal("User Profile", "Updated successfully.", "success");

            $('#tblUsers').DataTable('.dataTables-grdUsers');
        }else{
            swal("User Profile", res.msg, "error");
        }
    }catch(e){
        swal("User Profile", e.message(), "error");
    }
}
//////////////////////////////////////////////////
///////////     Add User Detail     /////////////
////////////////////////////////////////////////
function add_user_pre(){
    swal({
        title: 'Add New User',
        html:add_edit_user_view(),
        showCancelButton: true,
        focusConfirm: true,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        onOpen: function() {
            $("#data").datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "dd/mm/yyyy"
            });
        },
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtUsrName').val() == "" || $('#txtUsrFullName').val() == "" || $('#txtUsrPass').val() == "" || $('#selUsrTypID').val() == "" || $('#txtUsrDOB').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
            resolve([
                $('#txtUsrName').val(),
                $('#txtUsrPass').val(),
                $('#txtUsrFullName').val(),
                $('#txtUsrEmail').val(),
                $('#txtUsrMobile').val(),
                $('#txtUsrTell').val(),
                $('#txtUsrAddress').val(),
                $('#selUsrTypID').val(),
                $('#txtUsrDOB').val()
            ])
            })
        }
    })
    .then(function (result) {
        swal.showLoading();
        add_user(JSON.parse(JSON.stringify(result)));
    })
    .catch(swal.noop);
}
function add_user(usrDetail){
    var usrDOB = usrDetail[8].toString().split('/');
    $.ajax({
        url: 'users/add_user',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'usrName':usrDetail[0],'usrPass':usrDetail[1],'usrFullName':usrDetail[2],'usrEmail':usrDetail[3],'usrMobile':usrDetail[4],'usrTell':usrDetail[5],'usrAddress':usrDetail[6],'usrTypID':usrDetail[7],'usrDOB':moment(usrDOB[1]+'/'+usrDOB[0]+'/'+usrDOB[2]).format('YYYY/MM/DD')},
        success: onSuccess_add_user,
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_add_user(res){
    try{
        if(res.status == 200){
            var row = res.result[0];
            var usrTyp = 0;
            var usrStatus = '';
            if(row.usrEnable){usrStatus = 'fa-check"';}else{usrStatus = 'fa-times';}
            if(row.usrTypID == '0'){usrTyp = 'Admin';}else if(row.usrTypID == '1'){usrTyp = 'Employee';}
            var usrDOB = row.usrDOB.split("-");
            usrDOB = usrDOB[2] +'/'+ usrDOB[1] +'/'+ usrDOB[0];
            // var strRow = '<tr id="'+ row.usrID +'">'+
            //                 ''+
            //                 '<td id="tdUsrName'+ row.usrID +'">'+ row.usrName +'</td>'+
            //                 '<td id="tdUsrMobile'+ row.usrID +'">'+ row.usrMobile +'</td>'+
            //                 '<td id="tdUsrTell'+ row.usrID +'">'+ row.usrTell +'</td>'+
            //                 '<td id="tdUsrTyp'+ row.usrID +'">'+ usrTyp +'</td>'+
            //                 '<td><button class="btn btn-primary btn-circle" type="button">'+
            //                             '<i class="fa '+ usrStatus +'></i>'+
            //                             '</button></td>'+
            //                 '<td><a title="Edit" class="btn btn-primary btn-icon" id="btnEdit'+ row.usrID +'" onclick="btn_edit_user_view_toggle(this,'+ row.usrID +');" href="javascript:void(0);">'+
            //                 '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'+
            //                 '</a>'+
            //                 '<a title="Delete" class="btn btn-danger btn-icon" id="btnDisable'+ row.usrID +'" onclick="btn_disable_user(this,'+ row.usrID +');" href="javascript:void(0);">'+
            //                     '<i class="fa fa-trash" aria-hidden="true"></i>'+
            //                     '</a></td>'+
            //             '</tr>';

                        $('.dataTables-grdUsers').DataTable().row.add([
                        '<td id="tdUsrName'+ row.usrID +'">'+ row.usrName +'</td>',
                        '<td id="tdUsrFullName'+ row.usrID +'">'+ row.usrFullName +'</td>',
                        '<td id="tdUsrMobile'+ row.usrID +'">'+ row.usrMobile +'</td>',
                        '<td id="tdUsrTell'+ row.usrID +'">'+ row.usrTell +'</td>',
                        '<td id="tdUsrTyp'+ row.usrID +'">'+ usrTyp +'</td>',
                        '<td><button class="btn btn-primary btn-circle" type="button">'+
                                        '<i class="fa '+ usrStatus +'></i>'+
                                        '</button></td>',
                        '<td><a title="Edit" class="btn btn-primary btn-icon" id="btnEdit'+ row.usrID +'" onclick="btn_edit_user_view_toggle(this,'+ row.usrID +');" href="javascript:void(0);">'+
                            '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'+
                            '</a>'+
                            '<a title="Delete" class="btn btn-danger btn-icon" id="btnDisable'+ row.usrID +'" onclick="btn_disable_user(this,'+ row.usrID +');" href="javascript:void(0);">'+
                                '<i class="fa fa-trash" aria-hidden="true"></i></a>'+
                            '<a title="Change Password" class="btn btn-warning btn-icon" id="btnChangePass'+ row.usrID +'" onclick="btn_change_pass(this,'+ row.usrID +');" href="javascript:void(0);" >'+
                            '<i class="fa fa-key" aria-hidden="true"></i></a>'+
                        '</td>'
                        ]).draw();
                // strRow += '<tr id="trUsr'+row.usrID+'Edit" style="display:none;">'+
                //             '<td colspan="15">'+
                //                 '<div class="row">'+
                //                     '<div class="col-sm-12 text-center">'+
                //                     '<div class="alert alert-danger alert-dismissable" id="divError'+ row.usrID +'" style="display:none;" onclick="this.style.display = \"none\";">'+
                //                         '<button class="close" aria-hidden="true" id="close" type="button">×</button>'+
                //                         '<span id="spnError'+ row.usrID +'"></span>'+
                //                     '</div>'+
                //                     '</div>'+
                //                     '<div class="col-md-12 form-horizontal">'+
                //                     '<div class="form-group">'+
                //                         '<label class="col-sm-2 control-label" runat="server">Full Name*</label>'+
                //                         '<div class="col-sm-10">'+
                //                             '<input placeholder="Full Name" id="usrFullName'+ row.usrID +'" value="'+ row.usrFullName +'" class="form-control m-b " type="text">'+
                //                         '</div>'+
                //                         '<label class="col-sm-2 control-label" runat="server">Email</label>'+
                //                         '<div class="col-sm-10">'+
                //                             '<input placeholder="Email" id="usrEmail'+ row.usrID +'" value="'+ row.usrEmail +'" class="form-control m-b " type="email">'+
                //                         '</div>'+
                //                         '<label class="col-sm-2 control-label" runat="server">Mobile*</label>'+
                //                         '<div class="col-sm-10">'+
                //                             '<input placeholder="Mobile" id="usrMobile'+ row.usrID +'" value="'+ row.usrMobile +'" class="form-control m-b " type="text">'+
                //                         '</div>'+
                //                         '<label class="col-sm-2 control-label" runat="server">Tel.</label>'+
                //                         '<div class="col-sm-10">'+
                //                             '<input placeholder="Tel" id="usrTell'+ row.usrID +'" value="'+ row.usrTell +'" class="form-control m-b " type="text">'+
                //                         '</div>'+
                //                         '<label class="col-sm-2 control-label" runat="server">Address</label>'+
                //                         '<div class="col-sm-10">'+
                //                             '<textarea type="text" placeholder="Address" id="usrAddress'+ row.usrID +'" class="form-control m-b ">'+ row.usrAddress +'</textarea>'+
                //                         '</div>'+
                //                         '<label class="col-sm-2 control-label">DOB*</label>'+
                //                         '<div class="col-sm-10">'+
                //                         '<div id="data'+ row.usrID +'" class="input-group date">'+
                //                             '<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input value="'+ usrDOB +'" id="usrDOB'+ row.usrID +'" readonly="readonly" class="form-control" type="text">'+
                //                         '</div>'+
                //                         '</div>'+
                //                     '</div>'+
                //                     '<div class="form-group">'+
                //                         '<div class="col-sm-2 col-sm-offset-2">'+
                //                             '<input id="btnUpdate'+ row.usrID +'" onclick="btn_update_user_detail(this,'+ row.usrID +');" class="btn btn-primary" value="Save changes" type="button">'+
                //                         '</div>'+
                //                         '<div class="col-sm-2">'+
                //                             '<input id="btnCancel'+ row.usrID +'" onclick="btn_edit_user_view_hide(this);" class="btn btn-primary" value="Cancel" type="button">'+
                //                         '</div>'+
                //                     '</div>'+
                //                     '</div>'+
                //                 '</div>'+
                //             '</td>'+
                //         '</tr>';
            //$('#grdUsers tr:last').after(strRow);
            swal("User Profile", "User Added Successfully!", "success");
        }else{
            swal("User Profile", res.msg, "error");
        }
    }catch(e){
        swal("User Profile", e.message, "error");
    }
}
//////////////////////////////////////////////////
///////////     Disable User Detail     /////////
////////////////////////////////////////////////
function btn_disable_user(e, usrID){
    var alertText = '';
    var usrEnable = $(e).data('id');

    if(usrEnable){alertText = 'Enable';}else{alertText = 'Disable';}
    if (usrID != "" && usrID != 0){
        swal({
        title: "Disable Employee!",
        text: "Are you sure you want to '"+ alertText +"' this employee?",
        icon: "question",
        showLoaderOnConfirm: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: "Yes, do it!",
        defeat:true,
        })
        .then((value) => {
            if(value)
                disable_user(e, usrEnable, usrID);
        });
    }
}
function disable_user(e, usrEnable, usrID){
    $.ajax({
        url: 'users/disable_user',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'usrID':usrID, 'usrEnable': usrEnable},
        success: onSuccess_disable_user(e,usrEnable),
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_disable_user(e, usrEnable){
    return function(res){
        try{
        if(res.status == 200){
            if(usrEnable == '1'){
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
///////////     Delete User Detail     /////////
////////////////////////////////////////////////
function btn_delete_user(e, usrID){
    var alertText = '';
    var usrDeleted = $(e).data('id');

    if(usrDeleted){alertText = 'Delete';}else{alertText = 'Restore';}
    if (usrID != "" && usrID != 0){
        swal({
        title: "Disable Employee!",
        text: "Are you sure you want to '"+ alertText +"' this employee?",
        icon: "warning",
        showLoaderOnConfirm: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: "Yes, do it!",
        defeat:true,
        })
        .then((value) => {
            if(value)
                delete_user(e, usrDeleted, usrID);
        });
    }
}
function delete_user(e, usrDeleted, usrID){
    $.ajax({
        url: 'users/delete_user',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'usrID':usrID, 'usrDeleted': usrDeleted},
        success: onSuccess_delete_user(e, usrDeleted),
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_delete_user(e, usrDeleted){
    return function(res){
        try{
        if(res.status == 200){
            if(usrDeleted == '1'){
                $(e).data('id','0');
                //$(e).removeClass('btn-danger').addClass('btn-primary').find('i').removeClass('fa-undo').addClass('fa-trash');
                $('.dataTables-grdUsers').DataTable().row($(e).closest('tr')).remove().draw();
            }else{
                $(e).data('id','1');
                //$(e).removeClass('btn-primary').addClass('btn-danger').find('i').removeClass('fa-trash').addClass('fa-undo');
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
//////////////////////////////////////////////////
///////////     change Password     /////////////
////////////////////////////////////////////////
function btn_change_pass(e, usrID){
     swal({
        title: 'Change password',
        html:
        '<div class="row">'+
            '<div class="col-sm-12 text-center">'+
                '<div class="alert alert-danger alert-dismissable" id="divErrorAddUser" style="display:none;">'+
                '<button class="close" aria-hidden="true" id="close" type="button" onclick="this.style.display = \'none\';">×</button>'+
                '<span id="spnErrorAddUser">Please fill all mendatory(*) fields!</span>'+
                '</div>'+
            '</div>'+
            '<div class="col-sm-12 form-horizontal">'+
                '<div class="form-group">'+
                    '<label class="col-sm-5 control-label">Old Password<font color="red">*</font></label>'+
                    '<div class="col-sm-7">'+
                        '<input type="text" placeholder="Old Password" id="txtusrPassOld" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-5 control-label">New Password<font color="red">*</font></label>'+
                    '<div class="col-sm-7">'+
                        '<input type="password" placeholder="New Password" id="txtUsrPass" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-5 control-label">Confirm Password<font color="red">*</font></label>'+
                    '<div class="col-sm-7">'+
                        '<input type="text" placeholder="Confirm Password" id="txtUsrPassConfirm" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<input type="hidden" id="txtUsrID" value="'+ usrID +'">'+
                '</div>'+
            '</div>'+
        '</div>',
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtUsrID').val() == "" || $('#txtUsrID').val() == 0){reject("Unexpected error, please contact system administrator!");}
                if($('#txtusrPassOld').val() == "" || $('#txtUsrPass').val() == "" || $('#txtUsrPassConfirm').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
                 if($('#txtUsrPass').val() != $('#txtUsrPassConfirm').val())
                {reject("Password and Confirm Password fields do not match!");}
            resolve([
                $('#txtusrPassOld').val(),
                $('#txtUsrPass').val(),
                $('#txtUsrPassConfirm').val(),
                $('#txtUsrID').val()
            ])
            })
        }
    })
    .then(function (result) {
        change_pass(JSON.parse(JSON.stringify(result)));
    })
    .catch(swal.noop)
}
function change_pass(passDetail){
    $.ajax({
        url: 'users/change_pass',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'usrID':passDetail[3], 'usrPassOld':passDetail[0],'usrPass':passDetail[1]},
        success: onSuccess_change_pass,
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
function onSuccess_change_pass(res){
    try{
        if(res.status == 200){
            swal("User Profile", res.msg, "success");
        }else if(res.status == 202){
            swal("User Profile", res.msg, "error");
        }else{
            swal("User Profile", res.msg, "error");
        }
    }catch(e){
        swal("User Profile", e.message, "error");
    }
}

/////////////////////////////////////////////////
///////////     Helping Methods     ////////////
///////////////////////////////////////////////
function add_edit_user_view(){
    var strView = '<div class="row">'+
            '<div class="col-sm-12 text-center">'+
                '<div class="alert alert-danger alert-dismissable" id="divErrorAddUser" style="display:none;">'+
                '<button class="close" aria-hidden="true" id="close" type="button" onclick="this.style.display = \'none\';">×</button>'+
                '<span id="spnErrorAddUser">Please fill all mendatory(*) fields!</span>'+
                '</div>'+
            '</div>'+
            '<div class="col-sm-12 form-horizontal">'+
                '<div class="form-group">'+
                    '<label class="col-sm-4 control-label" runat="server">Username<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Username" id="txtUsrName" value="" class="form-control m-b " required>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" runat="server">Password<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<input type="password" placeholder="Password" id="txtUsrPass" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" runat="server">Full Name<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Full Name" id="txtUsrFullName" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" >Email</label>'+
                    '<div class="col-sm-8">'+
                        '<input type="email" placeholder="Email" id="txtUsrEmail" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" >Mobile</label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Mobile" id="txtUsrMobile" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label">Tel.</label>'+
                    '<div class="col-sm-8">'+
                        '<input type="text" placeholder="Tel." id="txtUsrTell" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-4 control-label" >Address</label>'+
                    '<div class="col-sm-8">'+
                        '<Textarea type="text" placeholder="Address" id="txtUsrAddress" value="" class="form-control m-b "></Textarea>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label">User Type<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<select id="selUsrTypID" class="form-control m-b">'+
                            '<option value="">-Select Type-</option>'+
                            '<option value="0">Admin</option>'+
                            '<option value="1">Employee</option>'+
                        '</select>'+
                    '</div>'+
                    '<label class="col-sm-4 control-label">DOB<font color="red">*</font></label>'+
                    '<div class="col-sm-8">'+
                        '<div id="data" class="input-group date">'+
                            '<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input value="" id="txtUsrDOB" readonly="readonly" type="text" class="form-control">'+
                        '</div>'+
                    '</div>'+
                    '<input type="hidden" id="usrID" />'+
                '</div>'+
            '</div>'+
        '</div>';
        return strView;
}