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
                $('#content-guest tbody').prepend(html);
                $('#submit-guest').removeClass('d-none');
            });
        });
    },
    delGuest: function() {
        $('body').on('click', '.delete-guest', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');

            if (target.indexOf('New') > -1) {
                $(this).closest('tr').remove();
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
            if ($('#content-guest .tr-new').length <= 0) {
                $('#submit-guest').addClass('d-none');
            }

        });
    },
    submit: function() {
        $('body').on('submit', '#form-guest', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            if (EventGuest.formValidate()) {

                $.post(action, $(this).serialize(), function(json) {

                    var bool = true;
                    var msg = "";

                    for (var i = 0; i < json.length; i++) {
                        if (!json[i].success) {
                            bool = false;
                            if (json[i].hasOwnProperty('message')) {
                                msg = json[i].message;
                            }
                            break;
                        }
                    }

                    if (bool) {
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
                        if (msg != "") {
                            msg = json.message;
                        } else {
                            msg = "Erro na tentativa de enviar o convite.";
                        }
                        $.smallBox({
                            title: "Erro!",
                            content: "<i class='fa fa-clock-o'></i> <i>Erro na tentativa de incluir convidados</i>",
                            color: "#C46A69",
                            iconSmall: "fa fa-times fa-2x fadeInRight animated",
                            timeout: 8000
                        });
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);
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