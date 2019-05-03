EventGuest = {
    cont: 0,
    init: function() {
        EventGuest.addGuest();
        EventGuest.delGuest();
        EventGuest.submit();
    },
    addGuest: function() {
        $('body').on('click', '#guest-add', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            EventGuest.cont++;
            newId = EventGuest.cont;
            $.get(target, { newId: newId }, function(html) {
                $('#content-guest').append(html);
                $('#li-submit-guest').removeClass('d-none');
            });
        });
    },
    delGuest: function() {
        $('body').on('click', '.delete-guest', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');

            if (target.indexOf('New') > -1) {
                $(this).closest('li').remove();
            } else {
                $.SmartMessageBox({
                    title: 'Excluir convidado',
                    content: 'Tem certeza que deseja excluir este convidado?',
                    buttons: '[Não][Sim]'
                }, function(ButtonPressed) {
                    if (ButtonPressed === "Sim") {

                        $.post(url, $('#form-delete').serialize(), function(json) {
                            if (json.success) {
                                $.smallBox({
                                    title: "Sucesso!",
                                    content: "<i class='fa fa-clock-o'></i> <i>Convido removido com sucesso.</i>",
                                    color: "#659265",
                                    iconSmall: "fa fa-check fa-2x fadeInRight animated",
                                    timeout: 2000
                                });
                                setTimeout(function() {
                                    location.reload(true);
                                }, 2000);
                            } else {
                                $.smallBox({
                                    title: "Erro!",
                                    content: "<i class='fa fa-clock-o'></i> <i>" + json.message + "</i>",
                                    color: "#C46A69",
                                    iconSmall: "fa fa-times fa-2x fadeInRight animated",
                                    timeout: 4000
                                });
                            }
                        });
                    }
                });
            }
            if ($('#content-guest .message-text').length <= 0) {
                $('#li-submit-guest').addClass('d-none');
            }

        });
    },
    submit: function() {
        $('body').on('submit', '#form-guest', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            if (EventGuest.formValidate()) {

                $.post(action, $(this).serialize(), function(json) {
                    if (json.success) {
                        $.smallBox({
                            title: "Sucesso!",
                            content: "<i class='fa fa-clock-o'></i> <i>Convidado(s) incluído(s) com sucesso.</i>",
                            color: "#659265",
                            iconSmall: "fa fa-check fa-2x fadeInRight animated",
                            timeout: 2000
                        });
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);
                    } else {
                        $.smallBox({
                            title: "Erro!",
                            content: "<i class='fa fa-clock-o'></i> <i>Erro na tentativa de incluir convidados</i>",
                            color: "#C46A69",
                            iconSmall: "fa fa-times fa-2x fadeInRight animated",
                            timeout: 4000
                        });
                    }
                });

            } else {
                $.smallBox({
                    title: "Erro!",
                    content: "<i class='fa fa-clock-o'></i> <i>Todos os dados de convidados devem ser preenchidos. </i>",
                    color: "#C46A69",
                    iconSmall: "fa fa-times fa-2x fadeInRight animated",
                    timeout: 4000
                });
                return false;
            }

        })
    },
    formValidate: function() {
        return true;
        var error = false;
        /*         $('#form-guest input').each(function(index, element) {
                    if ($(element).val().length <= 0) {
                        error = true;
                        break;
                    }
                });
         */
        return error;
    }
}
$('document').ready(function() {
    EventGuest.init();
})