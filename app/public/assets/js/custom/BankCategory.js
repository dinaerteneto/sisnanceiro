BankCategory = {

    data: [],

    init: function() {
        // BankCategory.loadData();
        // BankCategory.initSelect2();
        BankCategory.formValidate();
    },

    initSelect2: function() {
        $('#bankinvoicedetails-bank_category_id').select2({
            placeholder: 'Selecione',
            data: BankCategory.data,
            width: '100%',
            escapeMarkup: function(m) {
                return m;
            },
            formatResult: function(d) {
                return d.html;
            },
            formatSelection: function(d) {
                return d.selection;
            },
            formatNoMatches: function(term) {
                return `<div style="text-align: center">
                    <b>${term}</b>, não encontrado!<br />
                    <b><a href="javascript:void(0)" onClick="BankCategory.add('${term}', 2)">Clique aqui para criar</a></b>
                </div>`;
            }
        });
    },

    add: function (term, bankCategoryId) {
        const url = $('#url').val();
        $.ajax({
            async: false,
            url: `${url}/bank-categories/create`,
            type: 'post',
            dataType: 'json',
            data: {
                'BankCategory[name]': term,
                'BankCategory[parent_category_id]': bankCategoryId
            },
            beforeSend: function() {
                $.blockUI({
                    theme: false,
                    message: 'Incluindo categoria',
                    baseZ: 999999,
                    css: {
                        top: '50%'
                    }
                });
            },
            success: function(json) {
                BankCategory.data.push({
                    id: json.id,
                    text: json.name,
                    html: "<div>" + json.name + "</div>",
                    selection: json.name
                });
                $('#bankinvoicedetails-bank_category_id').select2('destroy');
                BankCategory.initSelect2();
                $('#bankinvoicedetails-bank_category_id').val(json.id);
                $('#bankinvoicedetails-bank_category_id').trigger('change');
            },
            complete: function() {
                $.unblockUI();
                $.smallBox({
                    title: "Categoria incluída com sucesso",
                    content: "",
                    color: "#296191",
                    iconSmall: "fa fa-thumbs-up bounce animated",
                    timeout: 3000,
                    baseZ: 9999
                });
            }
        });
    },

    loadData: function () {
        const url = $('#url').val();
        $.ajax({
            async: false,
            url: `${url}/bank-categories/hierarchy-select-2`,
            dataType: 'json',
            data: {
                remove: false,
                main_parent_category_id: 2
            },
            success: function(json) {
                BankCategory.data = json.items;
            }
        });
    },

    formValidate: function() {
        $('#bank-category-form').validate({
            rules: {
                'BankCategory[name]': 'required'
            },
            messages: {
                'BankCategory[name]': 'Digite o nome da categoria'
            }
        });
    }
}

$('document').ready(function() {
    BankCategory.init();
})
