//Fungsi Menampilkan Data
function MenampilkanJudulGrafik() {
    var ProsesFilterGrafik = $('#ProsesFilterGrafik').serialize();
    $.ajax({
        type    : 'POST',
        url     : '_Page/RekapTransaksi/MenampilkanJudulGrafik.php',
        data    : ProsesFilterGrafik,
        success: function(data) {
            $('#MenampilkanJudulGrafik').html(data);
            $('#ModalFilterGrafik').modal('hide');
        }
    });
}


let ChartTransaksi = null;

function MenampilkanGambarGrafik() {
    const ProsesFilterGrafik = $('#ProsesFilterGrafik').serialize();

    $.ajax({
        url: '_Page/RekapTransaksi/ProsesMenampilkanGrafikTransaksi.php',
        type: 'POST',
        data: ProsesFilterGrafik,
        dataType: 'json',
        success: function(response) {
            if (!response || !Array.isArray(response.months) || !Array.isArray(response.amounts)) {
                console.error('Format data grafik tidak valid.', response);
                return;
            }

            const months = response.months;
            const amounts = response.amounts.map(value => Number(value) || 0);

            const options = {
                chart: {
                    type: 'line',
                    height: 400,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: false
                    }
                },
                series: [{
                    name: 'Transaksi',
                    data: amounts
                }],
                colors: ['#0d6efd'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 5,
                    strokeWidth: 2,
                    hover: {
                        size: 7
                    }
                },
                xaxis: {
                    categories: months,
                    labels: {
                        rotate: -45
                    },
                    axisBorder: {
                        show: true
                    },
                    axisTicks: {
                        show: true
                    }
                },
                yaxis: {
                    min: 0,
                    labels: {
                        formatter: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                grid: {
                    show: true,
                    borderColor: '#dee2e6',
                    strokeDashArray: 3,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    padding: {
                        top: 10,
                        right: 20,
                        bottom: 10,
                        left: 10
                    }
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                noData: {
                    text: 'Tidak ada data transaksi'
                }
            };

            const ContainerGrafik = document.querySelector('#MenampilkanGambarGrafik');

            if (!ContainerGrafik) {
                console.error('Container grafik tidak ditemukan.');
                return;
            }

            // Hapus grafik sebelumnya jika sudah ada
            if (ChartTransaksi !== null) {
                ChartTransaksi.destroy();
                ChartTransaksi = null;
            }

            // Buat dan render grafik baru
            ChartTransaksi = new ApexCharts(ContainerGrafik, options);
            ChartTransaksi.render();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
            console.error(xhr.responseText);
        }
    });
}

$(document).ready(function() {
    MenampilkanJudulGrafik();
    MenampilkanGambarGrafik();
});