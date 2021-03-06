<!DOCTYPE html>
<?PHP
$base_url2 =  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ?  "https" : "http");
$base_url2 .=  "://".$_SERVER['HTTP_HOST'];
$base_url2 .=  str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
?>
<!--[if lt IE 7]>      <html class="no-js sidebar-large lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js sidebar-large lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js sidebar-large lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js sidebar-large"> <!--<![endif]-->

<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <!-- BEGIN META SECTION -->
    <meta charset="utf-8">
    <title>Login Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="" name="description" />
    <meta content="themes-lab" name="author" />
    <!-- END META SECTION -->
    <!-- BEGIN MANDATORY STYLE -->
    <link href="<?=base_url();?>kasir-apotek/assets/css/icons/icons.min.css" rel="stylesheet">
    <link href="<?=base_url();?>kasir-apotek/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url();?>kasir-apotek/assets/css/plugins.min.css" rel="stylesheet">
    <link href="<?=base_url();?>kasir-apotek/assets/css/style.min.css" rel="stylesheet">
    <!-- END  MANDATORY STYLE -->
    <!-- BEGIN PAGE LEVEL STYLE -->
    <link href="<?=base_url();?>kasir-apotek/assets/css/animate-custom.css" rel="stylesheet">
    <!-- END PAGE LEVEL STYLE -->
    <script src="<?=base_url();?>kasir-apotek/assets/plugins/modernizr/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>picture/11.ico">
</head>

<body class="login fade-in" data-page="login" style="background-image: url(<?php echo base_url();?>/picture/blur-hospital_1203-7957.jpg); background-size: cover;">
    <!-- BEGIN LOGIN BOX -->
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4" >
                <div class="login-box clearfix animated flipInY" style="background-color: #02B25F;">
                    <div class="page-icon animated bounceInDown" style="background-color: #ffffff;">
                        <img src="<?=base_url();?>picture/nurse.png" alt="Key icon">
                    </div>
                    <div class="login-logo">
                            <img src="<?=base_url();?>picture/pharmacy-logo-clipart-1.jpg" style="width: 30%;">
                    </div>
                    <hr>
                    <div class="login-form">
                        <!-- BEGIN ERROR BOX -->
                        <?PHP if($this->session->flashdata('gagal')){ ?>
                          <div class="alert alert-danger">
                              <button type="button" class="close" data-dismiss="alert">×</button>
                              <h4>Error!</h4>
                              Password atau Username Salah
                          </div>
                        <?PHP } ?>
                        <!-- END ERROR BOX -->
                        <form action="<?php echo base_url(); ?>login_kasir_c/login" method="post">
                            <input type="text" placeholder="Username" name="username" class="input-field form-control">
                            <input type="password" placeholder="Password" name="password" class="input-field form-control">
                            <input type="text" placeholder="Shift" id="shift" name="shift" onkeyup="masuk_shift();" class="input-field form-control">
                            <button type="submit" id="button_submit" class="btn btn-login" disabled='disabled' style="background-color: #ffffff; color: black;">Login</button>
                        </form>
                    </div>
                </div>
                    <div style="width: 100%; margin-top: 4%;" class="animated flipInY">
                        <span class="btn btn-facebook btn-block" style="cursor: default; background-color: #02B25F; color: white; font-size: 14px;">© 2018 Aplikasi Rumah Sakit</span>
                    </div>
            </div>
        </div>
    </div>
    <!-- END LOCKSCREEN BOX -->
    <!-- BEGIN MANDATORY SCRIPTS -->
    <script src="<?=base_url();?>kasir-apotek/assets/plugins/jquery-1.11.js"></script>
    <script src="<?=base_url();?>kasir-apotek/assets/plugins/jquery-migrate-1.2.1.js"></script>
    <script src="<?=base_url();?>kasir-apotek/assets/plugins/jquery-ui/jquery-ui-1.10.4.min.js"></script>
    <script src="<?=base_url();?>kasir-apotek/assets/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END MANDATORY SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?=base_url();?>kasir-apotek/assets/plugins/backstretch/backstretch.min.js"></script>
    <script src="<?=base_url();?>kasir-apotek/assets/js/account.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script type="text/javascript">
      function masuk_shift(){
        var shift = $('#shift').val();
        if (parseInt(shift) > 3) {
          alert('Shift Tidak Ada');
          $('#button_submit').attr('disabled','disabled');
        }else if (shift == '' || shift == null) {
          $('#button_submit').attr('disabled','disabled');
        }else{
          $('#button_submit').removeAttr('disabled');
        }
      }
    </script>
</body>
</html>
