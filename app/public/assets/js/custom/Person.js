Person = {

    contNewContact: 0,
    contNewAddress: 0,

    init: function() {
        Person.searchCep();
        Person.changeTypePerson();
        Person.maskContact();
        Person.addContact();
        Person.addAddress();
        Person.removeContainer();

        $('#Customer_physical').trigger('change');
    },

    addContact: function() {
        $('#add-contact').on('click', function(e) {
            e.preventDefault();
            Person.contNewContact++;
            $.get('/person/add-contact', { id: 'N' + Person.contNewContact }, function(html) {
                $('#container-contact').append(html);
                Form.masks();
            });
        });
    },

    addAddress: function() {
        $('#add-address').on('click', function(e) {
            e.preventDefault();
            Person.contNewAddress++;
            $.get('/person/add-address', { id: 'N' + Person.contNewAddress }, function(html) {
                $('#container-address').append(html);
                Form.masks();
            });
        });
    },

    searchCep: function() {
        $('body').on('blur', '.mask-cep', function(e) {
            e.preventDefault();
            var zipcode = $(this).val();
            var id = $(this).attr('data-id');
            $.getJSON('https://viacep.com.br/ws/' + zipcode + '/json/?callback=?', function(json) {
                console.log(json);
                var zipcode = json.cep;
                var address = json.logradouro;
                var district = json.bairro;
                var city = json.localidade;
                var uf = json.uf;

                $('#PersonAddress_' + id + '_address').val(address);
                $('#PersonAddress_' + id + '_district').val(district);
                $('#PersonAddress_' + id + '_city').val(city);
                $('#PersonAddress_' + id + 'uf').val(uf);
            });
        });
    },

    changeTypePerson: function() {
        $('#Customer_physical').on('change', function(e) {
            var sValue = parseInt($(this).val());
            $('#Customer_cpf').removeClass('mask-cpf');
            $('#Customer_cpf').unmask();
            if (sValue == 1 || sValue != 0) {
                //pessoa fisica
                $('#Customer_cpf').mask('999.999.999-99');
                $('#Customer_name').prev().html('Nome');
                $('#Customer_cpf').prev().html('CPF');

                $('#container-lastname').show();
                $('#container-birthdate').show();
                $('#container-gender').show();
                $('#container-rg').show();
            } else {
                //pessoa juridica
                $('#Customer_name').prev().html('Razão social');
                $('#Customer_cpf').prev().html('CNPJ');
                $('#Customer_cpf').mask('99.999.999/9999-99');

                $('#container-lastname').hide();
                $('#container-birthdate').hide();
                $('#container-gender').hide();
                $('#container-rg').hide();
            }
        });
    },

    maskContact: function() {
        $('body').on('focus', '.person-contact-value', function(e) {
            $(this).unmask();
            var id = $(this).attr('data-id');
            var typeId = parseInt($('#PersonContact_' + id + '_person_contact_type_id').val());
            if (typeId >= 3 && typeId <= 5) {
                $(this).mask('"(99) 9999-9999"')
            }
            if (typeId == 2) {
                $(this).mask("(99) 99999-999?9");
            }
        });
    },

    removeContainer: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('body').on('click', '.remove-container', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var containerName = $(this).attr('data-target-container');
            var container = $(this).closest('.' + containerName);
            $.post(href, function(json) {
                if (json.success) {
                    $(container).remove();

                    $.smallBox({
                        title: "Atenção!",
                        content: "<i class='fa fa-clock-o'></i> <i>Estas alterações somente serão salvas, após clicar em salvar.</i>",
                        color: "#C46A69",
                        iconSmall: "fa fa-times fa-2x fadeInRight animated",
                        timeout: 4000
                    });
                }
            }, 'json');
        });
    }

}

$('document').ready(function() {
    Person.init();
});