<?php
// echo('test');
?>

<p style="padding: 10px;">مراکز بازرسی</p>
</br>
</br>



<!-- ~~~~~~~~~~~~~~ -->
<!--  AUTOCOMPLETE  -->
<!-- ~~~~~~~~~~~~~~ -->

<div class="autocomplete">
    <input type="text" id="searchInput" placeholder="نام یا کد..." class="form-control"
        style="width: 33%; border-radius: 25px !important;">
    <div class="autocomplete-results" id="searchResults"></div>
</div>
<script src="/php/units/autocomplete.js"></script>
<!-- <input id="autocomplete" class="form-control" style="width: 33%; border-radius: 25px !important;"> -->

<p></p>
</br>
</br>





<!-- ~~~~~~~~~~~~~~ -->
<!-- MAP AND BUTTON -->
<!-- ~~~~~~~~~~~~~~ -->

<div id="map"
    style="width: 80%; height: 600px; background-color: aquamarine; border:1px solid gray; border-radius: 13px !important;">
    this is map
</div>

<br>

<button type="button" class="m-4 btn btn-success" id="setViewBtn"
    style="border-radius: 15px !important; width: 25%; height: 48px;" onclick="setPosClicked();">
    برو به دفتر اسناد پزشکی
</button>

<script>

    var map = L.map('map').setView([29.615932, 52.522730], 8);

    const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([29.615932, 52.522730]).addTo(map);

    function setPosClicked() {
        map.setView([29.615932, 52.522730], 17);
    }

</script>