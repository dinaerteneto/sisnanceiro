<script type="text/javascript" src="{{ asset('assets/js/libs/chart-js/chart.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/libs/chart-js/utils.js') }}"></script>

<!-- <script type="text/javascript" src="{{ asset('assets/js/libs/chart-js/chartjs-plugin-piechart-outlabels.js') }}"></script> -->

<script type="text/javascript">
var aBackgrounds = [];
function setColor() {
    return randomColor = Math.floor(Math.random()*16777215).toString(16);
}
var valueLength = <?=$jsonValue;?>.length;
for (var i = 0; i < valueLength; i++ ) {
    aBackgrounds.push('#'+setColor());
}

var ctx = document.getElementById('chart-category-to-receive').getContext('2d');
    window.invoiceTotal = new Chart(ctx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: <?=$jsonValue;?>,
            backgroundColor: aBackgrounds,
            label: 'DESPESAS POR CATEGORIA'
        }],
        labels: <?=$jsonLabel;?>,
    },
    options: {

       legend: true,
       responsive: true,
       maintainAspectRatio: false,

       zoomOutPercentage: 80,

       layout: {
            padding: 10
       },

       legend: {
            display: true,
            position: 'right',
            fullWidth: false,
            labels: {
                boxWidth: 20
            },

            onClick: function() {
                return false;
            }
       },
       tooltips: {
        enabled: true
       }
       /*
       plugins: {
            // legend: false,

            outlabels : {
                // lineColor: '#000',
                // padding: 1,
                // color: 'black',
                // backgroundColor: '#fff',
                // borderColor: null,

                borderRadius: 5,
                display: true,
                text: "R$ %v.2",
                borderWidth: 1,
                lineWidth: 1,

                textAlign: 'center',

                stretch: 20,
                font: {
                    resizable: true,
                    minSize: 8,
                    maxSize: 10,
                },
            }
        }
        */
    }
});

var ctx = document.getElementById('chart-category-to-budget').getContext('2d');
    window.budgetTotal = new Chart(ctx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: <?=$jsonParentValue;?>,
            backgroundColor: aBackgrounds,
            label: 'DESPESAS POR CATEGORIA'
        }],
        labels: <?=$jsonParentLabel;?>,
    },
    options: {

       legend: true,
       responsive: true,
       maintainAspectRatio: false,

       zoomOutPercentage: 80,

       layout: {
            padding: 10
       },

       legend: {
            display: true,
            position: 'right',
            fullWidth: false,
            labels: {
                boxWidth: 20
            },

            onClick: function() {
                return false;
            }
       },
       tooltips: {
        enabled: true
       }
       /*
       plugins: {
            // legend: false,

            outlabels : {
                // lineColor: '#000',
                // padding: 1,
                // color: 'black',
                // backgroundColor: '#fff',
                // borderColor: null,

                borderRadius: 5,
                display: true,
                text: "R$ %v.2",
                borderWidth: 1,
                lineWidth: 1,

                textAlign: 'center',

                stretch: 20,
                font: {
                    resizable: true,
                    minSize: 8,
                    maxSize: 10,
                },
            }
        }
        */
    }
});

</script>
