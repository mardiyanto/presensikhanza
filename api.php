<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Location Detection with Mapbox GL JS</title>
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
      .info {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #fff;
        padding: 10px;
        border-radius: 5px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        z-index: 1;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <div id="info" class="info"></div>

    <script>
      mapboxgl.accessToken = 'pk.eyJ1IjoiaXRyc2thcnRpbmkiLCJhIjoiY2xpd2lqbDMwMzlrMjNsbGd3c3dnY3Q1ZSJ9.eFCToH4luPNjPxsxM6_kkg';

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const { latitude, longitude } = position.coords;

            const map = new mapboxgl.Map({
              container: 'map',
              style: 'mapbox://styles/mapbox/streets-v12',
              center: [longitude, latitude],
              zoom: 12,
            });

            const marker = new mapboxgl.Marker().setLngLat([longitude, latitude]).addTo(map);

            const geocoder = new MapboxGeocoder({
              accessToken: mapboxgl.accessToken,
              mapboxgl: mapboxgl,
            });

            map.addControl(geocoder);

            geocoder.on('result', (event) => {
              map.getSource('single-point').setData(event.result.geometry);
            });

            const info = document.getElementById('info');
            info.innerHTML = `Latitude: ${latitude.toFixed(6)}, Longitude: ${longitude.toFixed(6)}`;
          },
          (error) => {
            console.log('Error getting current location:', error);
          }
        );
      } else {
        console.log('Geolocation is not supported by this browser.');
      }
    </script>
  </body>
</html>

