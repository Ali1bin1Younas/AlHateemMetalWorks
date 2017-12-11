<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url(); ?>Assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>Assets/css/plugins/bootstrap-select/bootstrap-select.css" rel="stylesheet">

 <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <h2>Your seat reservations (<span data-bind="text: seats().length"></span>)</h2>
    <button data-bind="click: addSeat, enable: seats().length < 5">Reserve another seat</button>
    <table>
        <thead><tr>
            <th>Passenger name</th><th>Meal</th><th></th><th>Surcharge</th><th></th>
        </tr></thead>
        <!-- Todo: Generate table body -->
        <tbody data-bind="foreach: seats">
            <tr>
                <td data-bind="text: name"></td>
                <td data-bind="text: meal().mealName"></td>
                <td><select data-bind="options: $root.availableMeals, value: meal, optionsText: 'mealName'"></select></td>
                <td data-bind="text: formattedPrice"></td>
            </tr>
        </tbody>
    </table>
    <h3 data-bind="visible: totalSurcharge() > 0">
        Total surcharge: $<span data-bind="text: totalSurcharge().toFixed(2)"></span>
    </h3>

    <!-- Folders -->
    <ul class="folders" data-bind="foreach: folders">
    <li data-bind="text: $data,
    css: { selected: $data == $root.chosenFolderId() }, click: $root.goToFolder"></li>
    </ul>


    <!-- Mails grid -->
    <table class="mails" data-bind="with: chosenFolderData">
        <thead><tr><th>From</th><th>To</th><th>Subject</th><th>Date</th></tr></thead>
        <tbody data-bind="foreach: mails">
            <tr>
                <td data-bind="text: from"></td>
                <td data-bind="text: to"></td>
                <td data-bind="text: subject"></td>
                <td data-bind="text: date"></td>
            </tr>     
        </tbody>
    </table>


    <h3 data-bind="text: question"></h3>
    <p>Please distribute <b data-bind="text: pointsBudget"></b> points between the following options.</p>

    <table>
        <thead><tr><th>Option</th><th>Importance</th></tr></thead>
        <tbody data-bind="foreach: answers">
            <tr>
                <td data-bind="text: answerText"></td>
                <td data-bind="starRating: points"></td>
            </tr>    
        </tbody>
    </table>

    <h3 data-bind="fadeVisible: pointsUsed() > pointsBudget">You've used too many points! Please remove some.</h3>
    <p>You've got <b data-bind="text: pointsBudget - pointsUsed()"></b> points left to use.</p>
    <button data-bind="jqButton: { enable: pointsUsed() <= pointsBudget }, click: save">Finished</button>
    <!-- /.content-wrapper -->
<div>
<?php  $this->load->view('common/footer');  ?>

<!--Page Scripts -->
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/sweetalert/sweetalert2.0.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-select/bootstrap-select.js"></script>
<script src="<?php echo base_url(); ?>assets/js/knockout-3.4.2.js"></script>
<script src="<?php echo base_url(); ?>assets/js/custum/vouchers_view.js"></script>
<!-- Page-Level Scripts -->
<script>
    function SeatReservation(name, initialMeal) {
        var self = this;
        self.name = name;
        self.meal = ko.observable(initialMeal);
        
        self.formattedPrice = ko.computed(function() {
            var price = self.meal().price;
            return price ? "$" + price.toFixed(2) : "None";        
        });
    }

// Overall viewmodel for this screen, along with initial state
function ReservationsViewModel(question, pointsBudget, answers) {
    var self = this;

    // Non-editable catalog data - would come from the server
    self.availableMeals = [
        { mealName: "Standard (sandwich)", price: 0 },
        { mealName: "Premium (lobster)", price: 34.95 },
        { mealName: "Ultimate (whole zebra)", price: 290 },
        { mealName: "Legacy", price: 50 }
    ];    

    // Editable data
    self.seats = ko.observableArray([
        new SeatReservation("Steve", self.availableMeals[0]),
        new SeatReservation("Bert", self.availableMeals[0]),
        new SeatReservation("Mick", self.availableMeals[1]),
        new SeatReservation("Ebad", self.availableMeals[3])
    ]);
    
    self.addSeat = function() {
        self.seats.push(new SeatReservation("Shary", self.availableMeals[3]));
    }

    self.totalSurcharge = ko.computed(function() {
       var total = 0;
       for (var i = 0; i < self.seats().length; i++)
           total += self.seats()[i].meal().price;
       return total;
    });
    ////////////////////////////////////////////////////////////////////////
    self.folders = ['Inbox', 'Archive', 'Sent', 'Spam'];
    self.chosenFolderId = ko.observable();
    self.chosenFolderData = ko.observable();
    
    // Behaviours
    self.goToFolder = function(folder) {
        self.chosenFolderId(folder);
        $.get('http://learn.knockoutjs.com/mail', { folder: folder }, self.chosenFolderData);
    };
    self.goToFolder('Inbox');
    ///////////////////////////////////////////////////////////////////////
    ko.bindingHandlers.jqButton = {
        init: function(element) {
            $(element).button(); // Turns the element into a jQuery UI button
            alert('A');
        },
        update: function(element, valueAccessor) {
            var currentValue = valueAccessor();
            // Here we just update the "disabled" state, but you could update other properties too
            $(element).button("option", "disabled", currentValue.enable === false);
        }
    };

    ko.bindingHandlers.fadeVisible = {
        init: function(element, valueAccessor) {
            // Start visible/invisible according to initial value
            var shouldDisplay = valueAccessor();
            $(element).toggle(shouldDisplay);
        },
        update: function(element, valueAccessor) {
            // On update, fade in/out
            var shouldDisplay = valueAccessor();
            shouldDisplay ? $(element).fadeIn("slow") : $(element).fadeOut("slow");
        }
    };
    self.question = question;
    self.pointsBudget = pointsBudget;
    self.answers = $.map(answers, function(text) { return new Answer(text) });
    self.save = function() { alert('To do') };
                       
    self.pointsUsed = ko.computed(function() {
        var total = 0;
        for (var i = 0; i < this.answers.length; i++)
            total += this.answers[i].points();
        return total;        
    }, this);

    function Answer(text) { this.answerText = text; this.points = ko.observable(2); }
}

    ko.applyBindings(new ReservationsViewModel("Which factors affect your technology choices?", 10, [
    "Functionality, compatibility, pricing - all that boring stuff",
    "How often it is mentioned on Hacker News",    
    "Number of gradients/dropshadows on project homepage",        
    "Totally believable testimonials on project homepage"
    ]));

  </script>
</body>
</html>