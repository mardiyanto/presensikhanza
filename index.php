<?php
 session_start();
 require_once('conf/command.php');
 require_once('conf/conf.php');
 require_once('conf/paging.php');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
 header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
 header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
 header("Cache-Control: post-check=0, pre-check=0", false);
 header("Pragma: no-cache"); // HTTP/1.0
 $setting=  mysqli_fetch_array(bukaquery("select setting.nama_instansi,setting.alamat_instansi,setting.kabupaten,setting.propinsi,setting.kontak,setting.email,setting.logo from setting"));
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

      <title><?php title();?></title>

    <!-- Custom fonts for this template-->
    <link href="sys/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="sys/css/sb-admin-2.min.css" rel="stylesheet">

    <script src="js/jquery.min.js"></script>
    <script src="js/webcam.min.js"></script>
    <script type="text/javascript" src="js/jquery/jquery.js"></script>

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"><?php echo $setting["nama_instansi"]; ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php?page=Input">ABSEN</a>
      </li>
	     <li class="nav-item">
        <a class="nav-link" href="index.php?page=TampilDatang">DATANG</a>
      </li>
	     <li class="nav-item">
        <a class="nav-link" href="index.php?page=TampilPulang">PULANG</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
             

       <?php
           $halaman= validTeks(isset($_GET["page"])?$_GET["page"]:NULL);
           if($halaman=="Input"){
               include "inputdata.php";
           }elseif($halaman=="TampilDatang"){
               include "tampildatang.php";
           }elseif($halaman=="TampilPulang"){
               include "tampilpulang.php";
           }elseif($halaman=="GantiKeterangan"){
               include "ubah.php";
           }elseif($halaman=="Cari"){
               include "cari.php";
           }else{
               include "inputdata.php";
           }
        ?>
     
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="sys/vendor/jquery/jquery.min.js"></script>
    <script src="sys/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="sys/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="sys/js/sb-admin-2.min.js"></script>

</body>

</html>
