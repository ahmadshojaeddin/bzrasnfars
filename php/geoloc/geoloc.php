<p>موقعیت جغرافیایی</p>
<hr class="hr hr-blurry" />
<small>تنطیمات موقعیت جغرافیایی گوشی خود را روشن کنید و دکمه زیر را فشار دهید</small>
<small style="color: blue;">اول موقعیت جغرافیایی خود را از ماهواره دریافت کنید، سپس دکمه اشتراک گذاری را فشار
    دهید</small>
<small id="geolocText">طول و عرض جغرافیایی: دریافت نشده</small>
<button class="m-1 form-control" style="width: 20%; border: 1px solid; border-radius: 10px !important;" onclick="geolocClicked()">دریافت موقعیت
    جغرافیایی من</button>
<button class="m-1 form-control" style="width: 20%; border: 1px solid; border-radius: 10px !important;">اشتراک
    گذاری</button>

<script>
    function geolocClicked() {
        let geoloc_element = document.getElementById('geolocText');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                let text = `Latitude: ${latitude}<br/>Longitude: ${longitude}`;
                geoloc_element.innerHTML = text;
            });
        } else {
            geoloc_element.textContent = 'مرورگر اینترنت شما موقعیت جغرافیایی را پشتیبانی نمی کند!';
        }
    }
</script>