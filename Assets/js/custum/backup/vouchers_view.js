var pageNameV = 'Create Voucher';
var controllerNameV = 'vouchers';
//////////////////////////////////////////////////
///////////     View Record     //////////////
////////////////////////////////////////////////
var viewModelInit = true;
Number.prototype.getDecimals = function() {
    var num = parseFloat(this.toFixed(10));
    var match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
    if (!match)
        return 0;
    return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
}
ko.bindingHandlers.ddlSelect2 = {
    init: function(element, valueAccessor, allBindingsAccessor) {
        var select2options = {};
        if ($(element).is('select'))
        {
            select2options.width = $(element).attr('data-width');
            select2options.allowClear = ($(element).attr('data-placeholder'));
            select2options.formatNoMatches = function() { return "No matches found"; };
            if (select2options.allowClear) select2options.placeholder = $(element).attr('data-placeholder');
        }
        if ($(element).is('input'))
        {
            try{
                var select2options = {
                allowClear: true,
                placeholder: ' ',
                formatNoMatches: function() { return "No matches found"; },
                formatSearching: function() { return "Searching ..."; },
                ajax:
                {
                    url: "Vouchers/"+ $(element).attr('data-autocomplete'),
                    dataType: 'json',
                    width: 'copy',
                    data: function (term, page) { return { Term: term } },
                    results: function(data, page) { return data; }
                }};
            select2options.width = $(element).attr('data-width');
            }catch(e){alert(e.message);}
        }
        $(element).select2(select2options);
        var tr = $(element).select2('container').closest('tr');
        if (tr.attr('data-select2height'))
        {
            $(element).select2('container').find('.select2-choice').height(tr.attr('data-select2height'));
        }

        ko.utils.registerEventHandler(element, 'change', function ()
        {
            var observable = valueAccessor();
            var data = $(element).select2('data');
            if (data != null)
            {
                data = jQuery.extend(true, {}, data);
                delete data.element;
                delete data.disabled;
                delete data.locked;
            }
            observable(data);
        });

        ko.utils.domNodeDisposal.addDisposeCallback(element, function() {
            $(element).select2('destroy');
        });
    }
};
ko.bindingHandlers.autosize = {
    update: function (element, valueAccessor) {
        ko.utils.unwrapObservable(valueAccessor());
        $(element).trigger('autosize.resize');
    }
};
function ReservationsViewModel() {
    var self = this;

    self.Lines = ko.observableArray();

    self.issueDate = ko.observable();
    self.deliveryDate = ko.observable();
    self.VoucherDescription = ko.observable();
    self.referenceNo = ko.observable();
    self.supplier = ko.observable();

    function transactionLinesModel(){
        var self = this;
        self.Item = ko.observable();
        self.trackingCode = ko.observable(); 
        self.discount = ko.observable();
        self.discountType = ko.observable();
        self.discountAmount = ko.observable();
        self.qty = ko.observable();
        self.amount = ko.observable();
        self.description = ko.observable();

        self.Item.subscribe(function(data) { 
            if (data && data.description && data.description.length > 0) { 
                self.description(data.description); } 
            if (data && data.unitPrice && data.unitPrice.length > 0) { 
                self.amount(data.unitPrice); } 
            if (data && data.trackingCode && data.trackingCode.length > 0) { 
                self.trackingCode({ id: data.trackingCode }); 
            } 
            if (self.qty() == null || self.qty().length == 0) { 
                self.qty('1'); 
            } 
        });

        self.AmountAsNumber = ko.computed(function() { 
            var amount = Globalize.parseFloat((self.amount() || '').toString()); 
            if (isNaN(amount)) { amount = 0; }; 
            return amount; 
        });
        self.LineTotal = ko.computed(function() { 
            var qty = Globalize.parseFloat((self.qty() || '').toString()); 
            var amount = Globalize.parseFloat((self.amount() || '').toString()); 
            var discount = Globalize.parseFloat((self.discount() || '').toString()); 
            var discountAmount = Globalize.parseFloat((self.discountAmount() || '').toString());
            if (isNaN(qty)) { qty = 1; }; 
            if (isNaN(amount)) { amount = 0; }; 
            var subtotal = qty*amount; 
            if (!isNaN(discount) && discount != 0 && subtotal != 0) { 
                subtotal = (subtotal/100)*(100-discount); 
            }; 
            if (!isNaN(discountAmount) && discountAmount != 0) { 
                subtotal -= discountAmount; 
            }; 
            return subtotal; 
        });
        self.FormattedLineTotal = ko.computed(function() { 
            var total = self.LineTotal(); 
            return Globalize.format(total, 'n'+total.getDecimals());
        });
    }

    self.description = ko.observable();
    self.discount = ko.observable(); 
    self.discountType = ko.observable();
    self.qty = ko.observable();
    self.amount = ko.observable();
    self.discountAmount = ko.observable();

    self.discount.subscribe(function(data) {
        if (viewModelInit) return; 
        for (var i = 0; i < self.Lines().length; i++) { 
            self.Lines()[i].discount(null); 
            self.Lines()[i].discountAmount(null); 
        }; 
    });
    self.discountType.subscribe(function(data) { 
        if (viewModelInit) return; 
        for (var i = 0; i < self.Lines().length; i++) { 
            self.Lines()[i].discount(null); 
            self.Lines()[i].discountAmount(null); 
        }; 
    });

    self.RemoveLines = function(line) { self.Lines.remove(line); };
    self.AddLines = function() {self.Lines.push(new transactionLinesModel());};
    self.Add5Lines = function() { 
        for (var i = 0; i < 5; i++) 
            self.Lines.push(new transactionLinesModel()); 
    }; 
    self.Add10Lines = function() { 
        for (var i = 0; i < 10; i++) 
            self.Lines.push(new transactionLinesModel()); 
    }; 
    self.Add20Lines = function() { 
        for (var i = 0; i < 20; i++) 
        self.Lines.push(new transactionLinesModel()); 
    };
    
    self.createVoucher = function(){alert(ko.toJSON(this));};
  }


  function get_view(){
    $.ajax({
        url: controllerNameV+'/get_view_create',
        method: 'GET',
        success: onSuccess_get_view,
        error: function (res) {
            swal("Upexpected Error", "Please contact system administrator.", "error");
        },
        failure: function (res) {
            swal("Upexpected Error", "Please try again later.", "error");
        }
    });
}
/////////////////////////////////////////////////////////////////
function onSuccess_get_view(res){
    $('.page-contents').html(res); return;
    var viewModel = new ReservationsViewModel();
    swal({
        //title: controllerNameV,
        html: res,
        showCancelButton: false,
        showConfirmButton: false,
        focusConfirm: true,
        customClass: 'swal-wider-voucher',
        onOpen: function() {
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
            }catch(e){alert(e.message);}
        },
        onClose: function(){
            ko.cleanNode(document.getElementById("createVoucherView"));
        },
        preConfirm: function () {
            resolve(viewModel);
        }
    })
    .catch(swal.noop);
}
//////////////////////////////////////////////////
///////////     Add New Record     //////////////
////////////////////////////////////////////////
function add_pre(){
    swal({
        title: 'Create Voucher',
        html: insert_add_view(),
        showCancelButton: true,
        focusConfirm: true,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        onOpen: function() {
            get_attributes(null);
            $("#data").datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "dd/mm/yyyy"
            }).datepicker('setDate', new Date()).datepicker('update').val('');
        },
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtName').val() == "" || $('#txtFullName').val() == "" || $('#txtPass').val() == "" || $('#selTypID').val() == "" || $('#txtDOB').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
            resolve([
                $('#txtDateTimeCreated').val(),
                $('#txtPass').val(),
                $('#txtFullName').val(),
                $('#txtEmail').val(),
                $('#txtMobile').val(),
                $('#txtTell').val(),
                $('#txtAddress').val(),
                $('#selUsrTyp').val(),
                $('#txtDOB').val()
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
    var DOB = detail[8].toString().split('/');
    $.ajax({
        url: controllerNameUsers+'/add_record',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'name':detail[0],'pass':detail[1],'fullName':detail[2],'email':detail[3],'mobile':detail[4],'tell':detail[5],'address':detail[6],'typID':detail[7],'DOB':moment(DOB[1]+'/'+DOB[0]+'/'+DOB[2]).format('YYYY/MM/DD')},
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
            swal("User Profile", "User Added Successfully!", "success");
        }else{
            swal("User Profile", res.msg, "error");
        }
    }catch(e){
        swal("User Profile", e.message, "error");
    }
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
        url: 'users/update_detail',
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
        url: controllerNameUsers+'/get_attributes',
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
        url: controllerNameUsers+'/disable_record',
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
        url: controllerNameUsers+'/delete_record',
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
//////////////////////////////////////////////////
///////////     change Password     /////////////
////////////////////////////////////////////////
function btn_change_pass(e, ID){
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
                        '<input type="password" placeholder="Old Password" id="txtPassOld" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-5 control-label">New Password<font color="red">*</font></label>'+
                    '<div class="col-sm-7">'+
                        '<input type="password" placeholder="New Password" id="txtPass" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<label class="col-sm-5 control-label">Confirm Password<font color="red">*</font></label>'+
                    '<div class="col-sm-7">'+
                        '<input type="password" placeholder="Confirm Password" id="txtPassConfirm" value="" class="form-control m-b ">'+
                    '</div>'+
                    '<input type="hidden" id="txtID" value="'+ ID +'">'+
                '</div>'+
            '</div>'+
        '</div>',
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: "Save it!",
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return new Promise(function (resolve,reject) {
                if($('#txtID').val() == "" || $('#txtID').val() == 0){reject("Unexpected error, please contact system administrator!");}
                if($('#txtPassOld').val() == "" || $('#txtPass').val() == "" || $('#txtPassConfirm').val() == "")
                {reject("Please fill all mendatory(*) fields first!");}
                 if($('#txtPass').val() != $('#txtPassConfirm').val())
                {reject("Password and Confirm Password fields do not match!");}
            resolve([
                $('#txtPassOld').val(),
                $('#txtPass').val(),
                $('#txtPassConfirm').val(),
                $('#txtID').val()
            ])
            })
        }
    })
    .then(function (result) {
        change_pass(JSON.parse(JSON.stringify(result)));
    })
    .catch(swal.noop)
}
function change_pass(detail){
    $.ajax({
        url: controllerNameUsers+'/change_pass',
        method: 'GET',
        contentType: "application/json; charset:utf-8",
        dataType: 'json',
        data: {'ID':detail[3], 'passOld':detail[0],'pass':detail[1]},
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