<!DOCTYPE html>
<html lang="en">

<head>
    <base target="_top">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DI Yogyakarta</title>
    <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        html,
        body {
            height: 550px;
            margin: 0;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        h3 {
            text-align: center;
            font-family: 'Lobster', cursive;
            font-size: 16px;
            color: #404040;
            /* Warna Tomat */
            margin: 30px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            /* Bayangan teks */
            letter-spacing: 1px;
            /* Jarak antar huruf */
            transition: color 0.3s ease;
            /* Transisi warna */
        }

        h3:hover {
            color:#0000ff;
            /* Warna Oranye Merah Gelap saat hover */
        }
    </style>
</head>

<body>
    <h3>Sistem Informasi Geografis Persebaran Rumah Sakit Di Wilayah DI Yogyakarta</h3>
    <div id="map"></div>
    <script src="js/leaflet.ajax.js"></script>
    <script>
        const map = L.map('map').setView([-7.7956, 110.3695], 10);

        const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Define a custom hospital icon
        const hospitalIcon = L.icon({
            iconUrl: 'img/hospital1.png', // URL to the hospital icon image
            iconSize: [32, 37], // Size of the icon
            iconAnchor: [16, 37], // Point of the icon which will correspond to marker's location
            popupAnchor: [0, -28] // Point from which the popup should open relative to the iconAnchor
        });

        const hospitals = [{
                "name": "Rumah Sakit Umum Pusat (RSUP) Dr. Sardjito",
                "coords": [-7.7686036764390956, 110.37346615335416]
            },
            {
                "name": "Rumah Sakit Panti Rapih",
                "coords": [-7.777222683545431, 110.37616363556299]
            },
            {
                "name": "Rumah Sakit Bethesda Yogyakarta",
                "coords": [-7.78325307687982, 110.37821388832405]
            },
            {
                "name": "RS Jogja International Hospital",
                "coords": [-7.759822, 110.373854]
            },
            {
                "name": "RSUD Yogyakarta",
                "coords": [-7.8249531474949805, 110.37786156108672]
            },
            {
                "name": "RSI Hidayatullah",
                "coords": [-7.815316877847379, 110.38775184721963]
            },
            {
                "name": "Rumah Sakit Pratama Kota Yogyakarta",
                "coords": [-7.8155877778555265, 110.3738997650108]
            },
            {
                "name": "RS PKU Muhammadiyah Yogyakarta",
                "coords": [-7.801123920033097, 110.36225187666741]
            },
            {
                "name": "Siloam Hospitals Yogyakarta",
                "coords": [-7.783409126263141, 110.39087618832406]
            },
            {
                "name": "Rumah Sakit dr. Soetarto (DKT)",
                "coords": [-7.7855593125782185, 110.37681711224974]
            },
            {
                "name": "Rumah Sakit Mata Dr. YAP Yogyakarta",
                "coords": [-7.780774512426549, 110.37499845887628]
            },
            {
                "name": "RS Khusus Bedah An Nur Yogyakarta",
                "coords": [-7.777918319186497, 110.38695304721962]
            },
            {
                "name": "Rumah Sakit JIH",
                "coords": [-7.7575418184451745, 110.40356904721963]
            },
            {
                "name": "Gigihospital",
                "coords": [-7.783519176887841, 110.3768877999807]
            },
            {
                "name": "RSU Sakina Idaman",
                "coords": [-7.767299483199912, 110.36796442390639]
            },
            {
                "name": "Rumah Sakit Happy Land",
                "coords": [-7.793952384128949, 110.39192079998067]
            },
            {
                "name": "RS Ludira Husada Tama",
                "coords": [-7.792534812799482, 110.35301239384617]
            },
            {
                "name": "RSK Puri Nirmala",
                "coords": [-7.801103869495175, 110.37727043166453]
            },
            {
                "name": "RS AMC Muhammadiyah",
                "coords": [-7.799635413024928, 110.35171658832408]
            },
            {
                "name": "RSU Griya Mahardhika Yogyakarta",
                "coords": [-7.8385790785518, 110.3658205650108]
            },
            {
                "name": "RS PKU Muhammadiyah Yogyakarta (Gedung KH.Sudja')",
                "coords": [-7.800353237176832, 110.36187752239069]
            },
            {
                "name": "Rumah Sakit Khusus Bedah (RSKB) Ring Road Selatan Bantul",
                "coords": [-7.834148971298906, 110.36038197666743]
            },
            {
                "name": "Rumah Sakit UAD",
                "coords": [-7.7471205046580005, 110.42507509998069]
            },
            {
                "name": "RSU Bunga Bangsa Medika",
                "coords": [-7.784802219437376, 110.4291955650108]
            },
            {
                "name": "RSKIA Kahyangan",
                "coords": [-7.79821708427789, 110.34503204169751]
            },
            {
                "name": "RSKIA Sadewa",
                "coords": [-7.77094060377859, 110.41578969998069]
            },
            {
                "name": "Rumah Sakit Akademik UGM",
                "coords": [-7.743810082383762, 110.35028254721965]
            },
            {
                "name": "Rumah Sakit Khusus Ibu dan Anak Bhakti Ibu",
                "coords": [-7.81481249188444, 110.381591535563]
            },
            {
                "name": "RS Condong Catur",
                "coords": [-7.754418282752052, 110.4057380239064]
            },
            {
                "name": "Rumah Sakit Khusus Bedah Sinduadi",
                "coords": [-7.757303018436491, 110.36319066501078]
            },
            {
                "name": "RS BHAYANGKARA POLDA DIY",
                "coords": [-7.766198611965073, 110.47181565335414]
            },
            {
                "name": "RS PKU Muhammadiyah Gamping",
                "coords": [-7.800390620006287, 110.317705035563]
            },
            {
                "name": "Rumah Sakit Bedah Adelia",
                "coords": [-7.843210571530312, 110.38988213556301]
            },
            {
                "name": "RSU Rajawali Citra",
                "coords": [-7.848365316120234, 110.41011402753313]
            },
            {
                "name": "Rumah Sakit Permata Husada",
                "coords": [-7.865212883946023, 110.40795488034549]
            },
            {
                "name": "Rumah Sakit Umum Daerah (RSUD) Panembahan Senopati",
                "coords": [-7.892319302979539, 110.33780375705277]
            },
            {
                "name": "RSU PKU Muhammadiyah Bantul",
                "coords": [-7.886389276699646, 110.33018628394456]
            },
            {
                "name": "Rumah Sakit Universitas Islam Indonesia",
                "coords": [-7.908864921718147, 110.29565126500403]
            },
            {
                "name": "Rumah Sakit Umum Daerah ( RSUD ) Wonosari",
                "coords": [-7.95442847071398, 110.59981310303291]
            },
            {
                "name": "RS. PKU Muhammadiyah Wonosari",
                "coords": [-7.931986573468697, 110.58951342108378]
            },
            {
                "name": "RS Bethesda Wonosari",
                "coords": [-7.956468582340089, 110.6125160441035]
            },
            {
                "name": "RS Nur Rohmah",
                "coords": [-7.917364678237104, 110.55861437523643]
            },
            {
                "name": "RSUD Saptosari",
                "coords": [-8.036704893857037, 110.49921954266316]
            },
            {
                "name": "RSU Pelita Husada",
                "coords": [-7.982649112786734, 110.6289955352221]
            },
            {
                "name": "Rumah Sakit Panti Rahayu",
                "coords": [-7.94830807494438, 110.6537147719]
            },
            {
                "name": "Rumah Sakit Umum Daerah ( RSUD ) Wates",
                "coords": [-7.850353126476924, 110.14559706526391]
            },
            {
                "name": "RSU Kharisma Paramedika",
                "coords": [-7.857155174604874, 110.1528068426283]
            },
            {
                "name": "RSU Mitra Sehat",
                "coords": [-7.801375098870434, 110.28670270796691]
            },
            {
                "name": "Klinik Pratama PKU Muhammadiyah Wates",
                "coords": [-7.861236349998233, 110.14662703345883]
            },
            {
                "name": "RSU RIZKI AMALIA",
                "coords": [-7.881981703890569, 110.08379897356917]
            },
            {
                "name": "RS PKU MUHAMMADIYAH KULON PROGO",
                "coords": [-7.869398580352472, 110.13014754234023]
            },
            {
                "name": "RS Pura Raharja Medika",
                "coords": [-7.923809334453964, 110.20739515695864]
            },
            {
                "name": "Rumah Sakit Hermina Yogya",
                "coords": [-7.763260857871553, 110.4312415141736]
            },
            {
                "name": "RSUD Sleman",
                "coords": [-7.677527771262677, 110.33923102568009]
            },
            {
                "name": "RSU Queen Latifa Kulon Progo",
                "coords": [-7.860013902515769, 110.21134324400234]
            }


        ];

        hospitals.forEach(function(hospital) {
            L.marker(hospital.coords, {
                    icon: hospitalIcon
                }).addTo(map)
                .bindPopup(`<b>${hospital.name}</b>`);
        });

        // const polygon = L.polygon([
        //     [-7.553613944900737, 109.28880269066143],
        //     [-7.571627391296171, 109.2707645055284],
        //     [-7.574846923130693, 109.29645870394745]
        // ]).addTo(map).bindPopup('Zona Aman');

        const popup = L.popup()
            .setLatLng([-7.7956, 110.3695])
            .setContent('Hello, I am Yogyakarta.')
            .openOn(map);

        function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent(`You clicked the map at ${e.latlng.toString()}`)
                .openOn(map);
        }

        map.on('click', onMapClick);

        function popUp(f, l) {
            var out = [];
            if (f.properties) {
                for (key in f.properties) {
                    out.push(key + ": " + f.properties[key]);
                }
                l.bindPopup(out.join("<br />"));
            }
        }

        // var jsonTest = new L.GeoJSON.AJAX(["geojson/yogyakarta.geojson"],
        var jsonTest = new L.GeoJSON.AJAX(["geojson/id34_daerah_istimewa_yogyakarta_district.geojson"], {
            onEachFeature: popUp
        }).addTo(map);
    </script>
</body>

</html>