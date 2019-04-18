Delete = {
    init: function() {
        Delete.deleteRecord();
    },
    deleteRecord: function() {
        $('body').on('click', '.delete-record', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var title = $(this).attr('data-title');
            var ask = $(this).attr('data-ask');

            swal({
                    title: title,
                    text: ask,
                    type: 'warning',
                    showCancelButton: true,
                    // confirmButtonClass: "btn-danger",
                    confirmButtonText: "Sim, Excluir!",
                    cancelButtonText: "Cancelar",
                },
                function() {

                    $.post(url, function(json) {
                        if (json.success) {
                            swal("Parabéns!", json.message, "success")
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        } else {
                            swal("Ops!", json.message, "danger")
                        }
                    });
                });

        });
    },
};
$('document').ready(function() {
    Delete.init();
})