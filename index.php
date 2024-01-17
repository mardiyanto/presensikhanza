<?php
 session_start();
 require_once('conf/command.php');
 require_once('conf/conf.php');
 require_once('conf/paging.php');
 date_default_timezone_set("Asia/Bangkok");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <style>
        #map {
            height: 400px;
        }
    </style>
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
        <a class="nav-link" href="api.php">MAP</a>
      </li>
	 <!--   <li class="nav-item">
        <a class="nav-link" href="index.php?page=inputpulang">ABSEN PULANG</a>
      </li>
	     <li class="nav-item">
        <a class="nav-link" href="index.php?page=TampilDatang">DATANG</a>
      </li>
	     <li class="nav-item">
        <a class="nav-link" href="index.php?page=TampilPulang">PULANG</a>
      </li> -->
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
           }
           elseif($halaman=="Inputabsenfoto"){
            include "inputdatafoto.php";
           }elseif($halaman=="inputpulang"){
               include "inputpulang.php";
		   }elseif($halaman=="Inputadmin"){
               include "adm.php";  
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script>
        // Fungsi untuk menghitung jarak antara dua titik koordinat menggunakan Haversine formula
        function calculateDistance(lat1, lon1, lat2, lon2) {
            var earthRadius = 6371; // Radius bumi dalam kilometer

            var dLat = degToRad(lat2 - lat1);
            var dLon = degToRad(lon2 - lon1);

            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(degToRad(lat1)) * Math.cos(degToRad(lat2)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);

            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var distance = earthRadius * c;

            return distance * 1000; // Mengonversi jarak menjadi meter
        }

        // Fungsi untuk mengubah derajat menjadi radian
        function degToRad(deg) {
            return deg * (Math.PI / 180);
        }

        // Lokasi kantor (latitude dan longitude)
        var officeLatitude = -5.3553201;
        var officeLongitude = 104.9720316;

        // Inisialisasi peta Leaflet
        var map = L.map('map').setView([officeLatitude, officeLongitude], 15);

        // Menambahkan peta tile menggunakan Leaflet providers
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            maxZoom: 18
        }).addTo(map);

        // Menambahkan marker untuk lokasi kantor
        var officeMarker = L.marker([officeLatitude, officeLongitude]).addTo(map);

        // Membuat lingkaran dengan radius 100 meter
        var radius = L.circle([officeLatitude, officeLongitude], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.3,
            radius: 100
        }).addTo(map);

        // Memeriksa jarak saat mendapatkan lokasi pengguna
        function getLocation() {
            var statusElement = document.getElementById("status");
            var frmPresensi = document.getElementById("frmPresensi");

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var userLatitude = position.coords.latitude;
                    var userLongitude = position.coords.longitude;

                    var distance = calculateDistance(officeLatitude, officeLongitude, userLatitude, userLongitude);

                    if (distance <= 100) {
                        statusElement.textContent = "Anda berada dalam radius 100 meter dari kantor.";
                        frmPresensi.style.display = "block";
                    } else {
                        statusElement.textContent = "Anda harus berada dalam radius 100 meter dari kantor untuk mengakses absensi.";
                        frmPresensi.style.display = "none";
                    }
                }, function (error) {
                    if (error.code === error.PERMISSION_DENIED) {
                        statusElement.textContent = "Anda tidak memberikan izin untuk mengakses lokasi.";
                    } else {
                        statusElement.textContent = "Terjadi kesalahan dalam mendapatkan lokasi.";
                    }
                    frmPresensi.style.display = "none";
                });
            } else {
                statusElement.textContent = "Geolokasi tidak didukung oleh browser Anda.";
                frmPresensi.style.display = "none";
            }
        }

        // Memanggil fungsi getLocation saat halaman dimuat
        window.addEventListener("DOMContentLoaded", getLocation);
    </script>
</body>

</html>
