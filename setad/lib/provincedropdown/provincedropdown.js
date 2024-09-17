if (typeof window.provincesLoaded === 'undefined')
    window.provincesLoaded = true;

const provinces = [
    "آذربایجان شرقی",
    "آذربایجان غربی",
    "اردبیل",
    "اصفهان",
    "البرز",
    "ایلام",
    "بوشهر",
    "تهران",
    "چهارمحال و بختیاری",
    "خراسان جنوبی",
    "خراسان رضوی",
    "خراسان شمالی",
    "خوزستان",
    "زنجان",
    "سمنان",
    "سیستان و بلوچستان",
    "فارس",
    "قزوین",
    "قم",
    "کردستان",
    "کرمان",
    "کرمانشاه",
    "کهگیلویه و بویراحمد",
    "گلستان",
    "گیلان",
    "لرستان",
    "مازندران",
    "مرکزی",
    "هرمزگان",
    "همدان",
    "یزد"
];

function populateDropdown(selectElement) {
    provinces.sort().forEach((province, index) => {
        const option = document.createElement("option");
        option.value = index + 1;
        // option.text = `${index + 1}  o | o  ${province}`;
        // const spaces = '     '; // Five spaces here
        // const spaces = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; // Five non-breaking spaces
        const spaces = ' '.repeat(5);  // This will create 5 regular spaces
        option.text = `${index + 1}${spaces}|${spaces}${province}`;
        selectElement.add(option);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = document.querySelectorAll('.province-dropdown');
    dropdowns.forEach(populateDropdown);
});
