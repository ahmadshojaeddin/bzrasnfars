document.addEventListener("DOMContentLoaded", function () {

    // console.log('autocomplete.js');

    const searchInput = document.getElementById("searchInput");
    const searchResults = document.getElementById("searchResults");

    searchInput.addEventListener("input", function () {

        // console.log('autocomplete.js: input...');

        const searchText = searchInput.value.trim();

        if (searchText.length >= 2) {
            fetchSuggestions(searchText);
        } else {
            // searchResults.innerHTML = "";
            hideResults();
        }

    });


    searchInput.addEventListener("blur", function () {
        hideResults();
    });


    // Clear searchInput when focus is regained
    searchInput.addEventListener("focus", function() {
        if (searchInput.value.trim().length > 0) {
            searchInput.value = "";
        }
    });


    // searchResults.addEventListener("mousedown", function(event) {

    //     if (event.target.tagName === "DIV") {
    //         searchInput.value = event.target.textContent;
    //         console.log(searchInput.value + ' clicked');
    //         hideResults();
    //     }

    // });


    function fetchSuggestions(query) {

        console.log('autocomplete.js: input...');

        fetch("/php/units/autocomplete-drs.php?query=" + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                displaySuggestions(data);
            })
            .catch(error => console.error('Error fetching autocomplete suggestions:', error));

    }


    function displaySuggestions(suggestions) {

        searchResults.innerHTML = "";

        if (suggestions.length > 0) {

            suggestions.forEach(suggestion => {
                const option = document.createElement("div");
                option.textContent = suggestion;
                option.addEventListener("mousedown", function () {
                    searchInput.value = suggestion;
                    console.log(searchInput.value + ' clicked');
                    hideResults();
                });
                searchResults.appendChild(option);
            });

        } else {

            const option = document.createElement("div");
            option.textContent = "پیدا نشد";
            searchResults.appendChild(option);

        }

        searchResults.style.display = "block";

    }


    function hideResults() {
        // Hide autocomplete-results
        searchResults.style.display = "none";
        // setTimeout(function() {
        //     searchResults.style.display = "none";
        // }, 100);
    }

});