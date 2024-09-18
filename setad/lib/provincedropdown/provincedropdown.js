if (typeof window.provincesLoaded === 'undefined') {

    window.provincesLoaded = true;

    const provinces = [
        "آذربایجان شرقی", // 1
        "آذربایجان غربی",
        "اردبیل",
        "اصفهان",
        "البرز",
        "ایلام",
        "بوشهر",
        "تهران",
        "چهارمحال و بختیاری",
        "خراسان جنوبی", // 10
        "خراسان رضوی",
        "خراسان شمالی",
        "خوزستان",
        "زنجان",
        "سمنان",
        "سیستان و بلوچستان",
        "فارس",
        "قزوین",
        "قم",
        "کردستان", // 20
        "کرمان",
        "کرمانشاه",
        "کهگیلویه و بویراحمد",
        "گلستان",
        "گیلان",
        "لرستان",
        "مازندران",
        "مرکزی",
        "هرمزگان",
        "همدان", // 30
        "یزد"
    ];

    function populateDropdown(selectElement) {

        const option = document.createElement("option");
        option.value = 0;
        option.text = '( انتخاب کنید )';
        selectElement.add(option);

        provinces.forEach((province, index) => {
        // provinces.sort().forEach((province, index) => {
            const option = document.createElement("option");
            option.value = index + 1;
            const spaces = ' '.repeat(5);  // This will create 5 regular spaces
            // option.text = `${index + 1}${spaces}|${spaces}${province}`;
            option.text = `${province}`;
            selectElement.add(option);
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        const dropdowns = document.querySelectorAll('.province-dropdown');
        dropdowns.forEach(populateDropdown);
    });

}