<div class="map-area">
    <div class="gmap">
        <div id="googleMap"></div>
    </div>
</div>

<!-- gmap-script -->
<script>
    function initMap() {
        var locationRio = {
            lat: -37.814929,
            lng: 144.996617
        };
        var map = new google.maps.Map(document.getElementById('googleMap'), {
            zoom: 13,
            center: locationRio,
            gestureHandling: 'cooperative'
        });
        var marker = new google.maps.Marker({
            position: locationRio,
            map: map,
            title: 'Hello World!'
        });
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAH5RWCDq3WYxQy9XSPDrreRX-CY8rxJcY&amp;callback=initMap">
</script>
<!-- gmap-script -->