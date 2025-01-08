document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('input[name="carpool_opinion[satisfied]"]').forEach(input => {
    input.addEventListener("change", (e) => {
        const opinion = document.querySelector("#carpool_opinion_opinion");
        if(e.target.value === 0)
        {
            opinion.setAttribute("required",true);
        }
        else{
            opinion.setAttribute("required",false);
        }
    });
});
    const radios = document.querySelectorAll('input[name="carpool_opinion[mark]"]');

    // Function to get the selected value
    const getSelectedValue = () => {
        const selected = Array.from(radios).find(radio => radio.checked);
        return selected ? selected.value : null;
    };

    // Function to update star ratings
    const updateStarRating = () => {
        // Reset all labels to blank star
        for (let i = 0; i < 5; i++) {
            const label = document.querySelector(`label[for='carpool_opinion_mark_${i}']`);
            if (label) {
                label.style.backgroundImage = "url('../img/starBlank.png')";
            }
        }

        // Highlight selected stars
        const selectedValue = getSelectedValue();
        if (selectedValue !== null) {
            for (let i = 0; i < selectedValue; i++) {
                const label = document.querySelector(`label[for='carpool_opinion_mark_${i}']`);
                if (label) {
                    label.style.backgroundImage = "url('../img/starColor.png')";
                }
            }
        }
    };

    // Attach change event to each radio button
    radios.forEach(radio => {
        radio.addEventListener('change', updateStarRating);
    });

    // Initial update in case a radio button is pre-selected
    updateStarRating();
});

