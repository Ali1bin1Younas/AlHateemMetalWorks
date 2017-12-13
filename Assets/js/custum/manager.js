function PurchaseOrderViewModel() { 
    var self = this; 
    self.Date = ko.observable(); 
    self.Reference = ko.observable(); 
    self.Supplier = ko.observable(); 
    self.Lines = ko.observableArray(); 
    
    function TransactionLineViewModel() { 
        var self = this; 
        self.Helper = ko.observable(); 
        self.Description = ko.observable(); 
        self.Account = ko.observable(); 
        self.TaxCode = ko.observable(); 
        self.Qty = ko.observable(); 
        self.Item = ko.observable(); 
        self.Amount = ko.observable(); 
        self.Debit = ko.observable(); 
        self.Credit = ko.observable(); 
        self.Discount = ko.observable(); 
        self.TrackingCode = ko.observable(); 
        self.ProposedAccountAmount = ko.observable(); self.CustomFields = ko.observableDictionary(); 
        self.DiscountAmount = ko.observable(); 
        self.MemberAccount = ko.observable(); 
        self.BillableExpenseCustomer = ko.observable(); 
        self.Disbursement = ko.observable(); 
        self.Invoice = ko.observable(); self.Obsolete_Disbursement = ko.observable(); 
        self.Obsolete_Account = ko.observable(); self.Obsolete_SalesInvoice = ko.observable(); 
        self.Obsolete_PurchaseInvoice = ko.observable(); self.Obsolete_Employee = ko.observable(); 
        self.Obsolete_ExpenseClaimPayer = ko.observable(); self.Obsolete_IntangibleAsset = ko.observable(); 
        self.Obsolete_InventoryItem = ko.observable(); self.Obsolete_Customer = ko.observable(); 
        self.Obsolete_Supplier = ko.observable(); self.Obsolete_FixedAsset = ko.observable(); 
        self.Obsolete_Member = ko.observable(); self.Obsolete_Item = ko.observable(); 
        self.Obsolete_PurchaseInvoiceItem = ko.observable(); self.Obsolete_Discount = ko.observable(); 
        self.Obsolete_EquityReason = ko.observable(); self.Obsolete_CurrencyAmount = ko.observable(); 
        self.Obsolete_DisbursementStatus = ko.observable(); self.Obsolete_DisbursementSalesInvoice = ko.observable(); 
        self.Obsolete_DisbursementWriteOffDate = ko.observable(); self.Obsolete_Cheque = ko.observable(); 
        self.Obsolete_BankDeposit = ko.observable(); self.Obsolete_BankAccount = ko.observable(); 
        self.Obsolete_CashAccount = ko.observable(); self.Obsolete_Amount = ko.observable(); 
        self.Obsolete_Invoice = ko.observable(); 
        
        self.Item.subscribe(function(data) { 
            if (viewModelInit) return; 
            if (data && data.Description && data.Description.length > 0) { 
                self.Description(data.Description); 
            } 
            if (data && data.TaxCode && data.TaxCode.length > 0) { 
                self.TaxCode({ id: data.TaxCode }); 
            } 
            if (data && data.UnitPrice && data.UnitPrice.length > 0) { self.Amount(data.UnitPrice); } 
            if (data && data.TrackingCode && data.TrackingCode.length > 0) { 
                self.TrackingCode({ id: data.TrackingCode }); 
            } 
            if (self.Qty() == null || self.Qty().length == 0) { 
                self.Qty('1'); 
            } 
        }); 
        self.Helper.subscribe(function(data) { 
            if (viewModelInit) return; 
            if (data && data.TaxCode && data.TaxCode.length > 0) { 
                self.TaxCode({ id: data.TaxCode }); 
            } 
            self.Account(data && data.Account ? { id: data.Account } : null); 
        }); 
        self.Account.subscribe(function(data) { 
            if (viewModelInit) return; 
            self.Invoice(null); 
            if (data && data.TaxCode && data.TaxCode.length > 0) { 
                self.TaxCode({ id: data.TaxCode }); 
            } 
        }); 
        self.LineTotal = ko.computed(function() { 
            var qty = Globalize.parseFloat((self.Qty() || '').toString()); 
            var amount = Globalize.parseFloat((self.Amount() || '').toString()); 
            var discount = Globalize.parseFloat((self.Discount() || '').toString()); 
            var discountAmount = Globalize.parseFloat((self.DiscountAmount() || '').toString()); 
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
        self.AmountAsNumber = ko.computed(function() { 
            var amount = Globalize.parseFloat((self.Amount() || '').toString()); 
            if (isNaN(amount)) { amount = 0; }; 
            return amount; 
        }); 
        self.DebitAsNumber = ko.computed(function() { 
            var debit = Globalize.parseFloat((self.Debit() || '').toString()); 
            if (isNaN(debit)) { debit = 0; }; 
            return debit; 
        }); 
        self.CreditAsNumber = ko.computed(function() { 
            var credit = Globalize.parseFloat((self.Credit() || '').toString()); 
            if (isNaN(credit)) { credit = 0; }; return credit; 
        }); 
    } 
            
    self.AddLines = function() { self.Lines.push(new TransactionLineViewModel()); }; 
    self.Add5Lines = function() { 
        for (var i = 0; i < 5; i++) 
            self.Lines.push(new TransactionLineViewModel()); 
    }; 
    self.Add10Lines = function() { 
        for (var i = 0; i < 10; i++) 
            self.Lines.push(new TransactionLineViewModel()); 
    }; 
    self.Add20Lines = function() { 
        for (var i = 0; i < 20; i++) 
        self.Lines.push(new TransactionLineViewModel()); 
    }; 
    self.RemoveLines = function(line) { self.Lines.remove(line); }; 
    self.DeliveryInstructions = ko.observable(); 
    self.AuthorizedBy = ko.observable(); 
    self.DeliveryDate = ko.observable(); 
    self.DeliveryAddress = ko.observable(); 
    self.AmountsIncludeTax = ko.observable(); 
    self.CustomFields = ko.observableDictionary(); 
    self.Description = ko.observable(); 
    self.Discount = ko.observable(); 
    self.DiscountType = ko.observable(); 
    self.Key = ko.observable(); 
    self.Discount.subscribe(function(data) { 
        if (viewModelInit) return; 
        for (var i = 0; i < self.Lines().length; i++) { 
            self.Lines()[i].Discount(null); 
            self.Lines()[i].DiscountAmount(null); 
        }; 
    }); 
    self.DiscountType.subscribe(function(data) { 
        if (viewModelInit) return; 
        for (var i = 0; i < self.Lines().length; i++) { 
            self.Lines()[i].Discount(null); 
            self.Lines()[i].DiscountAmount(null); 
        }; 
    }); 
}

var viewModelInit = true;
var viewModel = new PurchaseOrderViewModel();
viewModel.Date("2017-12-12");
viewModel.Reference("");
viewModel.AddLines();
viewModel.Lines()[0].Helper(null);
viewModel.Lines()[0].Description("");
viewModel.Lines()[0].Amount("0");
viewModel.Lines()[0].Obsolete_EquityReason("");
viewModel.Lines()[0].Obsolete_DisbursementStatus("Uninvoiced");
viewModel.DeliveryInstructions("");
viewModel.AuthorizedBy("");
viewModel.DeliveryAddress("");
viewModel.AmountsIncludeTax(false);
viewModel.Description("");
viewModel.Discount(false);
viewModel.DiscountType("Percentage");
viewModel.Key({ id: "00000000-0000-0000-0000-000000000000", text: "#####"});
viewModel.Key({ id: "00000000-0000-0000-0000-000000000000", text: "#####"});
var viewModelInit = false;