<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AL-Hateem Metal | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - AH</title>

    <link href="<?php echo base_url(); ?>Assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>Assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>Assets/css/animate.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>Assets/css/style.css" rel="stylesheet">
</head>
<body class="hold-transition login-page">
  <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">AH</h1>
            </div>
            <h3>Welcome to Al-Hateem Metal Works</h3>
            <form class="m-t" role="form" action="<?php echo base_url();?>Login/login_verify" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="username" name="usrName" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="password" name="usrPass" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                <a href="#"><small>Forgot password?</small></a>
                <!--<p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a>-->
            </form>
        </div>
    </div>

<!-- Mainly scripts -->
    <script src="<?php echo base_url(); ?>Assets/js/jquery-2.1.1.js"></script>
    <script src="<?php echo base_url(); ?>Assets/js/bootstrap.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
