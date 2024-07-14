<div class="row">

    <div class="col-3" style="margin-left: 25px;">
        <p>مناطق بازرسی</p>
        </br>
        </br>
    </div>

    <div class="col" style="width: 80%;">
        <div class="row">
            <div class="col-3">
                <input type="text" class="form-control" id="region-name" aria-describedby="emailHelp"
                    placeholder="نام منطقه">
            </div>
            <div class="col-1">
                <div class="col-1 m-1"> <button
                    style="background-color: #ffaa11; border: 1px solid gray; border-radius: 10px !important;">          </button></div>
            </div>
            <div class="col-1 m-1"> <button
                    style="border: 1px solid gray; border-radius: 10px !important;">ویرایش</button></div>
            <div class="col-1 m-1"> <button
                    style="border: 1px solid gray; border-radius: 10px !important;">انصراف</button></div>
        </div>
        <hr class="hr hr-blurry" />
    </div>

</div>




<!-- ~~~~~~~~~~~~~~ -->
<!--  LIST AND MAP  -->
<!-- ~~~~~~~~~~~~~~ -->

<div class="row">

    <div class="col-3 bg-white"
        style="border:1px solid gray !important; margin-left: 25px; border-radius: 13px !important;">

        <div class="row p-3">
            <button class="m-1 bg-gray text-dark" style="width: 12%; border-radius: 7px !important;">+</button>
            <button class="m-1 bg-gray text-dark" style="width: 12%; border-radius: 7px !important;">-</button>
            <button class="m-1 bg-gray text-dark" style="width: 25%; border-radius: 7px !important;">...</button>
        </div>

        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">منطقه 1</h5>
                    <small>ویرایش</small>
                </div>
            </a>
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">منطقه 2</h5>
                    <small class="text-muted">ویرایش</small>
                </div>
            </a>
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">منطقه 3</h5>
                    <small class="text-muted">ویرایش</small>
                </div>
            </a>
        </div>


    </div>



    <div class="col" id="map"
        style="width: 80%; height: 600px; background-color: aquamarine; border:1px solid gray; border-radius: 13px !important;">
        this is map
    </div>

</div>

<br>



<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

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