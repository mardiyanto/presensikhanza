<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Location Distance Validation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
    <link
      href="https://api.tiles.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css"
      rel="stylesheet"
    />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js"></script>
    <link
      rel="stylesheet"
      href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css"
      type="text/css"
    />
    <style>
      body {
        margin: 0;
        padding: 0;
      }
      #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
      }
      .button {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
      }
      .button.disabled {
        background-color: #ccc;
        cursor: not-allowed;
      }
    </style>
  </head>
  <body>
    <button id="myButton" class="button">My Button</button>

    <script>
      mapboxgl.accessToken = 'pk.eyJ1IjoiaXRyc2thcnRpbmkiLCJhIjoiY2xpd2lqbDMwMzlrMjNsbGd3c3dnY3Q1ZSJ9.eFCToH4luPNjPxsxM6_kkg';

      const button = document.getElementById('myButton');

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            const center = [-5.357518, 104.972048]; // Ganti dengan koordinat pusat yang sesuai
            const distanceThreshold = 1000; // Ganti dengan jarak ambang batas yang diinginkan (dalam meter)

            const distance = getDistanceFromLatLonInM(latitude, longitude, center[1], center[0]);

            if (distance <= distanceThreshold) {
              button.classList.remove('disabled');
              button.disabled = false;
            } else {
              button.classList.add('disabled');
              button.disabled = true;
            }

            const map = new mapboxgl.Map({
              container: 'map',
              style: 'mapbox://styles/mapbox/streets-v12',
              center: center,
              zoom: 12,
            });

            const marker = new mapboxgl.Marker().setLngLat(center).addTo(map);

            const geocoder = new MapboxGeocoder({
              accessToken: mapboxgl.accessToken,
              mapboxgl: mapboxgl,
            });

            map.addControl(geocoder);

            geocoder.on('result', (event) => {
              map.getSource('single-point').setData(event.result.geometry);
            });
          },
          (error) => {
            console.log('Error getting current location:', error);
          }
        );
      } else {
        console.log('Geolocation is not supported by this browser.');
      }

      function getDistanceFromLatLonInM(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the earth in km
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a =
          Math.sin(dLat / 2) * Math.sin(dLat / 2) +
          Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c * 1000; // Distance in meters
        return distance;
      }

      function deg2rad(deg) {
        return deg * (Math.PI / 180);
      }
    </script>
  </body>
</html>