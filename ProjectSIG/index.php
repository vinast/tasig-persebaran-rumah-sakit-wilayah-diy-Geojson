<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>projectsig</title>
    <style>
        /* ukuran peta */
        #mapid {
            height: calc(100vh - 80px);
            min-height: 400px;
        }

        .jumbotron {
            height: 100%;
            border-radius: 0;
        }

        body {
            background-color: #ebe7e1;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            height: 100vh;
            padding: 0;
        }

        h1 {
            text-align: center;
            font-family: 'Lobster', cursive;
            font-size: 16px;
            color: #404040;
            margin: 30px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
            transition: color 0.3s ease;
        }

        h1:hover {
            color: #0000ff;
        }

        h2 {
            font-family: 'Arial', sans-serif;
            font-size: 1.5rem;
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }

        form .form-group {
            margin-bottom: 15px;
        }

        .btn {
            display: block;
            width: 100%;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            height: 100%;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-y: auto;
            transition: transform 0.3s ease;
            transform: translateX(-100%);
            z-index: 1000;
        }

        #sidebar.active {
            transform: translateX(0);
        }

        #sidebarToggle {
            position: absolute;
            top: 4px;
            left: 2px;
            background-color: #262626;
            color: white;
            border: 0.5;
            padding: 10px 15px;
            cursor: pointer;
            z-index: 1100;
        }

        #mapid {
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }

        #mapid.active {
            margin-left: 300px;
        }

        @media (max-width: 768px) {
            #sidebar {
                width: 100%;
                transform: translateX(-100%);
            }

            #mapid.active {
                margin-left: 0;
            }
        }
    </style>
    <!-- leaflet css -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <!-- bootstrap cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://unpkg.com/leaflet-ajax/dist/leaflet.ajax.min.js"></script>
    <script src="js/leaflet.ajax.js"></script>
</head>

<body>

    <button id="sidebarToggle">☰</button>
    <h1>Sistem Informasi Geografis Persebaran Rumah Sakit Wilayah DIY</h1>

    <div id="sidebar">
        <h2>Tambah Data Rumah Sakit</h2>
        <hr>
        <form id="rsForm" action="proses.php" method="post">
            <div class="form-group">
                <label for="latlong">Latitude, Longitude</label>
                <input type="text" class="form-control" id="latlong" name="latlong" required>
            </div>
            <div class="form-group">
                <label for="nama_tempat">Nama Tempat</label>
                <input type="text" class="form-control" name="nama_tempat" id="nama_tempat" required>
            </div>
            <div class="form-group">
                <label for="kecamatan">Kecamatan</label>
                <input type="text" class="form-control" name="kecamatan" id="kecamatan" required>
            </div>
            <div class="form-group">
                <label for="kabupaten">Kabupaten</label>
                <input type="text" class="form-control" name="kabupaten" id="kabupaten" required>
            </div>
            <div class="form-group">
                <label for="kode_pos">Kode Pos</label>
                <input type="text" class="form-control" name="kode_pos" id="kode_pos" required>
            </div>
            <div class="form-group">
                <label for="no_telepon">No Telepon</label>
                <input type="text" class="form-control" name="no_telepon" id="no_telepon" required>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control" name="keterangan" id="keterangan" cols="20" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-info">Add</button>
            </div>
        </form>
    </div>

    <div id="mapid"></div>

    <!-- leaflet js -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('mapid').classList.toggle('active');
        });

        // set lokasi latitude dan longitude, lokasinya kota palembang
        var mymap = L.map('mapid').setView([-7.8015680011314785, 110.36564564698467], 12);

        const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        });

        const osmHOT = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>'
        });

        const baseLayers = {
            'OpenStreetMap': osm,
            'OpenStreetMap.HOT': osmHOT
        };

        // Add the base layers to the map
        L.control.layers(baseLayers).addTo(mymap);

        osm.addTo(mymap); // Add the default base layer to the map

        // buat variabel berisi fungsi L.popup
        var popup = L.popup();
        // buat fungsi popup saat map diklik
        function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent("koordinatnya adalah " + e.latlng.toString())
                .openOn(mymap);
            document.getElementById('latlong').value = e.latlng
        }
        mymap.on('click', onMapClick);

        <?php
        $mysqli = mysqli_connect('localhost', 'root', '', 'db_projectsig');
        $tampil = mysqli_query($mysqli, "SELECT * FROM rumahsakit");
        while ($hasil = mysqli_fetch_array($tampil)) {
            $latlong = str_replace(['[', ']', 'LatLng', '(', ')'], '', $hasil['lat_long']);
        ?>
            L.marker([<?php echo $latlong; ?>]).addTo(mymap)
                .bindPopup(`<?php echo 'nama tempat: ' . $hasil['nama_tempat'] . '<br>keterangan: ' . $hasil['keterangan'] . '<br>kecamatan: ' . $hasil['kecamatan'] . '<br>kabupaten: ' . $hasil['kabupaten'] . '<br>kode pos: ' . $hasil['kode_pos'] . '<br>no telepon: ' . $hasil['no_telepon']; ?>`);
        <?php } ?>

        // Function to handle popups for each feature
        function popUp(feature, layer) {
            if (feature.properties && feature.properties.name) {
                layer.bindPopup(feature.properties.name);
            }
        }

        // Define the style for the GeoJSON layer
        function style(feature) {
            return {
                fillColor: 'blue',
                weight: 2,
                opacity: 1,
                color: 'blue',  // Border color
                fillOpacity: 0.5
            };
        }

        // Load the GeoJSON file
        var jsonTest = new L.GeoJSON.AJAX("geojson/yogyakarta.geojson", {
            style: style, // Apply the style here
            onEachFeature: popUp
        }).addTo(mymap);

        // Form validation
        document.getElementById('rsForm').addEventListener('submit', function(event) {
            var latlong = document.getElementById('latlong').value;
            var nama_tempat = document.getElementById('nama_tempat').value;
            var kecamatan = document.getElementById('kecamatan').value;
            var kabupaten = document.getElementById('kabupaten').value;
            var kode_pos = document.getElementById('kode_pos').value;
            var no_telepon = document.getElementById('no_telepon').value;
            var keterangan = document.getElementById('keterangan').value;

            if (!latlong || !nama_tempat || !kecamatan || !kabupaten || !kode_pos || !no_telepon || !keterangan) {
                alert('Semua field harus diisi.');
                event.preventDefault();
                return false;
            }

            if (!/^\d+$/.test(kode_pos)) {
                alert('Kode Pos harus berupa angka.');
                event.preventDefault();
                return false;
            }

            if (!/^\d{12}$/.test(no_telepon)) {
                alert('No Telepon harus terdiri dari 12 angka.');
                event.preventDefault();
                return false;
            }

            return true;
        });
    </script>
</body>

</html>
