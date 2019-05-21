Form = {
    init: function() {
        Form.masks();
        Form.checkAll();
    },
    masks: function() {
        Form.unmasks();
        $('.mask-date').mask('99/99/9999');
        $('.mask-phone').focusout(function() {
            var phone, element;
            element = $(this);
            element.unmask();
            phone = element.val().replace(/\D/g, '');
            if (phone.length > 10) {
                element.mask("(99) 99999-999?9");
            } else {
                element.mask("(99) 9999-9999?9");
            }
        }).trigger('focusout');
        $('.mask-processo').mask('9999999-99.9999.9.99.9999');
        $('.mask-cellphone').mask('(99) 9999-9999?9');
        $('.mask-date-time').mask('99/99/9999 99:99:99');
        $('.mask-cep').mask('99999-999');
        $('.mask-cpf').mask('999.999.999-99');
        $('.mask-cnpj').mask('99.999.999/9999-99');
        $('.mask-number').keypress(function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
        $('.mask-time').mask('99:99');

        $('.hasClockpicker').clockpicker({
            placement: 'bottom',
            donetext: 'Done'
        });


        $('.mask-month-year').mask('99/9999');
        $('.mask-year').mask('9999');

        $('.datepicker').datepicker({
                dateFormat: 'dd/mm/yy'
            })
            .datepicker("option",
                "monthNames", ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'])
            .datepicker("option", "dayNamesMin", ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab']);

        $('.mask-float').maskMoney({
            allowZero: false,
            affixesStay: true,
            allowNegative: true,
            symbol: '',
            decimal: ',',
            thousands: '.',
            symbolPosition: 'left',
            suffix: ''
        }).trigger('mask.maskMoney');

        $('.mask-float-precision3').maskMoney({
            symbol: '',
            decimal: '.',
            thousands: '',
            precision: 3
        }).trigger('mask.maskMoney');

        $('.mask-currency').maskMoney({
            symbol: 'R$',
            decimal: ',',
            thousands: '.'
        }).trigger('mask.maskMoney');

        $('.money-negative').maskMoney({
            symbol: '',
            allowNegative: true,
            thousands: '.',
            decimal: ',',
            affixesStay: false,
            allowZero: true
        }).trigger('mask.maskMoney');

        $('.spinner').spinner();

    },
    unmasks: function() {
        $('input').each(function(index, element) {
            if ($(element).attr('class')) {
                var sClass = $(element).attr('class');
                if (sClass.indexOf('mask') > -1) {
                    $(element).removeClass();
                    // $(element).unmask();
                    // $(element).unmaskMoney();
                    $(element).addClass(sClass);
                }
            }
        });
    },
    checkAll: function() {
        $('input.check-all').on('click', function() {
            var bCheckbox = $(this).is(':checked');
            $('table tr td input:checkbox').prop('checked', bCheckbox);
        });
    }
};

$('document').ready(function() {
    Form.init();
});