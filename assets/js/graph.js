function graph(tableau,place){
    var xValues = ["Lundi", "Mardi", "Mercredi", "jeudi", "Vendredi","Samedi","Dimanche"];
    var yValues = tableau;
    var barColors = ['#5FACD3','#5FACD3',"#5FACD3","#5FACD3","#5FACD3","#5FACD3","#5FACD3"];

    new Chart(place, {
    type: "bar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: barColors,
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: ""
        }
    }
    });    
}
window.graph = graph;



