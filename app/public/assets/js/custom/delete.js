Delete = {
    init: function() {
        Delete.deleteRecord();
    },
    deleteRecord: function() {
        $('body').on('click', '.delete-record', function(e) {
            e.preventDefault();
            var objTr = $(this).parents('tr');
            var url = $(this).attr('href');
            var title = $(this).attr('data-title');
            var ask = $(this).attr('data-ask');


            $.SmartMessageBox({
                title: title,
                content: ask,
                buttons: '[Não][Sim]'
            }, function(ButtonPressed) {
                if (ButtonPressed === "Sim") {

                    $.post(url, $('#form-delete').serialize(), function(json) {
                        if (json.success) {
                            $.smallBox({
                                title: "Sucesso!",
                                content: "<i class='fa fa-clock-o'></i> <i>Registro removido com sucesso</i>",
                                color: "#659265",
                                iconSmall: "fa fa-check fa-2x fadeInRight animated",
                                timeout: 2000
                            });

                            if (json.hasOwnProperty('remove-tr')) {
                                var table = $('#dt_basic').DataTable();
                                table
                                    .row(objTr)
                                    .remove()
                                    .draw();
                            } else {
                                setTimeout(function() {
                                    location.reload(true);
                                }, 2000);
                            }
                        } else {
                            const msg = json.message ? json.message : 'Erro na tentativa de excluir';
                            $.smallBox({
                                title: "Erro!",
                                content: `<i class='fa fa-clock-o'></i> <i>${json.message}</i>`,
                                color: "#C46A69",
                                iconSmall: "fa fa-times fa-2x fadeInRight animated",
                                timeout: 4000
                            });
                        }
                    });
                }
            });
        });
    },
};
$('document').ready(function() {
    Delete.init();
})
