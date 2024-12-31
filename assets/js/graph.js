import Chart from 'chart.js/auto';
import { format } from 'date-fns';

// Object to store Chart.js instances
const chartInstances = {};
let CurrentDate = new Date();
let CurrentDateCredit = new Date();
let startWeek = document.getElementById("startWeek");
let endWeek = document.getElementById("endWeek");
let startWeekCredit = document.getElementById("startWeekCredit");
let endWeekCredit = document.getElementById("endWeekCredit");

function getDayOfWeek(date, chosenStartDay) {
    if (!date || isNaN(new Date(date).getTime())) {
        console.log(date);
        throw new Error("Invalid date passed to getDayOfWeek");
    }
    let nowDate = new Date(date);
    chosenStartDay = chosenStartDay % 7; // Normalize 7 to 0 (Sunday)
    let currentDay = nowDate.getDay();
    let distanceToStartDay =
        currentDay >= chosenStartDay
            ? chosenStartDay - currentDay
            : chosenStartDay - currentDay - 7;
    nowDate.setDate(nowDate.getDate() + distanceToStartDay);
    return nowDate;
}

// Graph function with chart instance management
function graph(tableau, placeId) {
    const xValues = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
    const barColors = ['#5FACD3', '#5FACD3', "#5FACD3", "#5FACD3", "#5FACD3", "#5FACD3", "#5FACD3"];

    const place = document.getElementById(placeId);

    // Destroy the existing chart instance if it exists
    if (chartInstances[placeId]) {
        chartInstances[placeId].destroy();
    }

    // Create a new chart instance and store it
    chartInstances[placeId] = new Chart(place, {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: tableau
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            }
        }
    });
}

// Function to fetch data and render a chart
function fetchAndRenderChart(redirection, dataValue, chartId, date) {
    fetch(`/${redirection}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ data: dataValue, currentDate: date })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        graph([
            data.dayCounts.Monday,
            data.dayCounts.Tuesday,
            data.dayCounts.Wednesday,
            data.dayCounts.Thursday,
            data.dayCounts.Friday,
            data.dayCounts.Saturday,
            data.dayCounts.Sunday
        ], chartId);
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });
}

// DOMContentLoaded: Initial chart rendering
document.addEventListener("DOMContentLoaded", function () {
    fetchAndRenderChart("carpoolAvg",null, "NbCarpoolChart", CurrentDate);
    // Calculate the start of the current week (e.g., Monday)
    let startOfWeek = getDayOfWeek(CurrentDate, 1); // 1 for Monday
    // Calculate the end of the current week (e.g., Sunday)
    let endOfWeek = new Date(startOfWeek); // Clone the start date
    endOfWeek.setDate(startOfWeek.getDate() + 6);
    startWeek.innerHTML = format(startOfWeek, 'dd/MM/yyyy');
    endWeek.innerHTML = format(endOfWeek, 'dd/MM/yyyy');
    startWeekCredit.innerHTML = format(startOfWeek, 'dd/MM/yyyy');
    endWeekCredit.innerHTML = format(endOfWeek, 'dd/MM/yyyy');
    fetchAndRenderChart("nbCreditAvg",null,"nbCreditsChart",CurrentDateCredit)
});

// Click event listener: Handle button clicks
document.addEventListener("click", (e) => {

    if (e.target.id === "CountCarpoolRight") {
        CurrentDate.setDate(CurrentDate.getDate() + 7);
        let startOfWeek = getDayOfWeek(CurrentDate, 1); // 1 for Monday
        // Calculate the end of the current week (e.g., Sunday)
        let endOfWeek = new Date(startOfWeek); // Clone the start date
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        startWeek.innerHTML = format(startOfWeek, 'dd/MM/yyyy');
        endWeek.innerHTML = format(endOfWeek, 'dd/MM/yyyy');
        fetchAndRenderChart("carpoolAvg","CountCarpoolRight", "NbCarpoolChart", CurrentDate);
    }
    else if(e.target.id === "CountCarpoolLeft"){
        CurrentDate.setDate(CurrentDate.getDate() - 7);
        let startOfWeek = getDayOfWeek(CurrentDate, 1); // 1 for Monday
        // Calculate the end of the current week (e.g., Sunday)
        let endOfWeek = new Date(startOfWeek); // Clone the start date
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        startWeek.innerHTML = format(startOfWeek, 'dd/MM/yyyy');
        endWeek.innerHTML = format(endOfWeek, 'dd/MM/yyyy');
        fetchAndRenderChart("carpoolAvg","CountCarpoolLeft", "NbCarpoolChart",CurrentDate);
    }
    else if(e.target.id === "CountCreditLeft"){
        CurrentDateCredit.setDate(CurrentDateCredit.getDate() - 7);
        let startOfWeekCredit = getDayOfWeek(CurrentDateCredit, 1); // 1 for Monday
        // Calculate the end of the current week (e.g., Sunday)
        let endOfWeekCredit = new Date(startOfWeekCredit); // Clone the start date
        endOfWeekCredit.setDate(startOfWeekCredit.getDate() + 6);
        startWeekCredit.innerHTML = format(startOfWeekCredit, 'dd/MM/yyyy');
        endWeekCredit.innerHTML = format(endOfWeekCredit, 'dd/MM/yyyy');
        fetchAndRenderChart("nbCreditAvg","CountCreditLeft", "nbCreditsChart",CurrentDateCredit);
    }
    else if(e.target.id === "CountCreditRight"){
        CurrentDateCredit.setDate(CurrentDateCredit.getDate() + 7);
        let startOfWeek = getDayOfWeek(CurrentDateCredit, 1); // 1 for Monday
        // Calculate the end of the current week (e.g., Sunday)
        let endOfWeek = new Date(startOfWeek); // Clone the start date
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        startWeekCredit.innerHTML = format(startOfWeek, 'dd/MM/yyyy');
        endWeekCredit.innerHTML = format(endOfWeek, 'dd/MM/yyyy');
        fetchAndRenderChart("nbCreditAvg","CountCreditRight", "nbCreditsChart",CurrentDateCredit);
    }
});




