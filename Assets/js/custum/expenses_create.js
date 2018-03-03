var pageNameV = 'Expenses';
var controllerNameV = 'Expenses';
var win_loc = document.getElementById("callBackLoc").value;
//////////////////////////////////////////////////
///////////     Add New Record     //////////////
////////////////////////////////////////////////
function onSuccess_add_record(viewModel, isNew){
    return function(res){
        try{
            viewModel.LoadingVoucherEnable(false);
            if(res.status == 200){
                swal("Expenses", "Record created successfully!", "success");
                if(isNew){
                    var d = new Date();
                    viewModel.issueDate(moment((d.getMonth()+1)+'/'+d.getDate()+'/'+d.getFullYear()).format('DD/MM/YYYY'));
                    viewModel.ID(Globalize.parseInt(viewModel.ID()) + 1);
                    viewModel.VoucherDescription('');
                    viewModel.persons('');
                    viewModel.Lines([]);
            
                    viewModel.AddLines();
                    viewModel.Lines()[0].description();
                    viewModel.Lines()[0].isBank(false);
                    viewModel.Lines()[0].amount("0");
                }else{
                    $(location).attr('href', $('#callBackLoc').val());
                }
            }else{
                swal("User Profile", "nope", "error");
            }
        }catch(e){
            swal("User Profile", e.message, "error");
        }
    }
}
/////////////////////////////////////////////////
///////////     Helping Methods     ////////////
///////////////////////////////////////////////