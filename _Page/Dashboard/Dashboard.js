$(document).ready(function () {
    // Fungsi untuk mengambil data dari file JSON
    $.getJSON("_Page/Dashboard/GrafikTransaksi.json", function (data) {
        // Mengolah data untuk ApexCharts
        const categories = data.map(item => item.x);
        const simpananSeries = data.map(item => parseFloat(item.ySimpanan));
        const pinjamanSeries = data.map(item => parseFloat(item.yPinjaman));

        // Konfigurasi grafik
        var options = {
            chart: {
                type: 'line',
                height: 400
            },
            series: [
                {
                    name: 'Simpanan',
                    data: simpananSeries
                },
                {
                    name: 'Pinjaman',
                    data: pinjamanSeries
                }
            ],
            xaxis: {
                categories: categories
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                    }
                }
            }
        };

        // Inisialisasi grafik
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
});