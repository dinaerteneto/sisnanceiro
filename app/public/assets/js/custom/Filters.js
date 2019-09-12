Filters = {
    init: function() {
        // initialize date-range-picker
        var start = $('#filter-range-start-date').val() == '' ? moment().subtract(30, 'days') : moment($('#filter-range-start-date').val());
        var end = $('#filter-range-end-date').val() == '' ? moment() : moment($('#filter-range-end-date').val());

        Filters.cb(start, end);
        Filters.initDateRangePicker(start, end);
        // Filters.postFilter();
    },
    cb: function(start, end) {
        $('#filter-range span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    },
    initDateRangePicker: function(start, end) {
        $('#filter-range').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontém': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 dias': [moment().subtract(29, 'days'), moment()],
                'Este mês': [moment().startOf('month'), moment().endOf('month')],
                'Último mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },

            autoApply: true,
            cancelClass: 'pull-right btn-danger',

            format: 'DD/MM/YYYY',
            separator: ' até ',

            locale: {
                applyLabel: 'OK',
                fromLabel: 'De',
                toLabel: 'Até',

                customRangeLabel: 'Personalizado',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                firstDay: 0
            },
        }, Filters.cb);


        $('#filter-range').on('cancel.daterangepicker', function() {
            $('#filter-range-start-date').val('');
            $('#filter-range-end-date').val('');
        });

        $('#filter-range').on('apply.daterangepicker', function(ev, picker) {
            $('#filter-range-start-date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#filter-range-end-date').val(picker.endDate.format('YYYY-MM-DD'));
        });
    }
}

$('document').ready(function() {
    Filters.init();
})