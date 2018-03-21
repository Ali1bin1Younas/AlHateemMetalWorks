        </div> <!-- div - page-contents -->
        <div class="footer">
            <div class="pull-right">
                
            </div>
            <div>
                <strong>Copyright</strong> Adroit Light Solutions &copy; 2017
            </div>
        </div>
    </div>
</div>

    <!-- Mainly scripts -->
    <script src="<?php echo base_url(); ?>Assets/js/jquery-2.1.1.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/jquery-ui-1.10.4.min.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/jquery-ui.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- Custom and plugin javascript -->
    <script src="<?php echo base_url(); ?>Assets/js/inspinia.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/plugins/pace/pace.min.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/moment.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/mouseTrape.js"></script>
    
    <script>
        var url = "<?php echo base_url() ?>";
        Mousetrap.bind('ctrl+k', function(e) {
            window.location =  url + "Sale/saleCreate";
        });
        Mousetrap.bind('ctrl+y', function(e) {
            window.location = url + "Purchase/purchaseCreate";
        });
    </script>
