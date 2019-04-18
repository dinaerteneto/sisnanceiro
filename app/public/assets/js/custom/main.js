Main = {
    init: function() {
        //$('#side-menu').metisMenu();
        $(window).bind("load resize", function() {
            topOffset = 50;
            height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
            height = height - topOffset;
            if (height < 1)
                height = 1;
            if (height > topOffset) {
                $("#sidemenu").css("min-height", (height) + "px");
            }
        });
        // Main.configBlock();
        Main.openModal();
    },
    configBlock: function() {
        //$(document).ajaxStart($.blockUI);
        $.blockUI.defaults.message = '<img src="images/core/load.gif" border="0" class="image-loading" />';
        $.blockUI.defaults.css.background = 'transparent';
        $.blockUI.defaults.css.border = '0';
        $.blockUI.defaults.css.padding = '15px';
        $.blockUI.defaults.baseZ = 999999;
    },
    openModal: function() {
        $('body').on('click', '.open-modal', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var target = $(this).attr('target');
            $.ajax({
                url: href,
                success: function(html) {
                    $(target).html(html);
                },
                complete: function() {
                    $(target).modal('show');
                    Form.init();
                }
            });
        });
    },

    closeModal: function() {
        $('.modal').on('hidden.bs.modal', function() {

        });
    },

    openReport: function() {
        $('body').on("click", "#w0-excel5", function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            $.ajax({
                url: href,
                dataType: 'json',
                success: function(json) {
                    Main.downloadURI(json.file, 'relatorio.xls');
                },
                complete: function() {}
            });
        });
    },

    downloadURI: function(uri, name) {
        location.href = uri;
    }
};

$('document').ready(function() {
    Main.init();
});