Store = {
    init: function() {
        Store.initSelect2();
        Store.reWriteOptionLabel();
        Store.addAttribute();
        Store.delAttribute();
        Store.changeWithAttributes();
        Store.formValidate();

        $('.with-attributes').hide();
        $('.without-attributes').show();
        var checked = $('#StoreProduct-with_attributes').is(':checked');
        if ($('#w0').attr('action').indexOf('update') > 0) {
            checked = $("#StoreProduct-with_attributes").val() == '1' ? true : false;
        }
        if (checked) {
            $(".with-attributes").show();
            $(".without-attributes").hide();
        }
        $('#StoreProdutct-attributes-0-value').tagbox({
            onChange: function(value) {
                Store.addSubproducts();
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // 'Content-Type': 'application/json',
                    // 'Accept': 'application/json'
            }
        });
    },
    initSelect2: function() {
        $('#StoreProduct_store_product_category_id').select2({
            formatNoMatches: function(term) {
                return "<div class='select2-result-label'><span class='select2-match'></span>" + term + " <span class='pull-right'><a href='javascript:void(0)' onClick=\"Store.addCategory('" + term + "')\"><i class='fa fa-plus-circle'></i> adicionar</a></span></div>";
            }
        });
        $('#StoreProduct_store_product_brand_id').select2({
            formatNoMatches: function(term) {
                return "<div class='select2-result-label'><span class='select2-match'></span>" + term + " <span class='pull-right'><a href='javascript:void(0)' onClick=\"Store.addBrand('" + term + "')\"><i class='fa fa-plus-circle'></i> adicionar</a></span></div>";
            }
        });
        $('#StoreProduct-attributes-first').select2({
            formatNoMatches: function(term) {
                return "<div class='select2-result-label'><span class='select2-match'></span>" + term + " <span class='pull-right'><a href='javascript:void(0)' onClick=\"Store.sendAttribute('" + term + "', 'storeproducts-attributes-first')\"><i class='fa fa-plus-circle'></i> adicionar</a></span></div>";
            },
            containerCss: {
                'min-width': '200px'
            }
        });
        $('#StoreProduct-store_product_category_id').select2({
            formatNoMatches: function(term) {
                return "<div class='select2-result-label'><span class='select2-match'></span>" + term + " <span class='pull-right'><a href='javascript:void(0)' onClick=\"Bank.openModalCategory('" + term + "')\"><i class='fa fa-plus-circle'></i> adicionar</a></span></div>";
            }
        });
    },
    loading: function() {
        $.blockUI({
            message: "Aguarde...",
            css: {
                top: "20%"
            }
        });
    },
    removeLoading: function() {
        $.unblockUI();
    },
    addCategory(term) {
        Store.loading();
        $.post('/store/product-category/create', {
            'StoreProductCategory[name]': term
        }, function(json) {
            $('#StoreProduct_store_product_category_id').append("<option value=\"" + json.id + "\">" + json.name + "</option>");
            $('#StoreProduct_store_product_category_id').val(json.id).trigger("change");
        }).done(function() {
            Store.removeLoading();
        });
    },
    addBrand(term) {
        Store.loading();
        $.post('/store/product-brand/create', {
            'StoreProductBrand[name]': term
        }, function(json) {
            $('#StoreProduct_store_product_brand_id').append("<option value=\"" + json.id + "\">" + json.name + "</option>");
            $('#StoreProduct_store_product_brand_id').val(json.id).trigger("change");
        }).done(function() {
            Store.removeLoading();
        });
    },
    addAttribute: function() {
        $('body').on('click', '#add-attribute', function(e) {
            e.preventDefault();
            var index = $('#table-attributes tbody tr').length + 1;
            var selectOptions = $('#table-attributes tbody tr:eq(0) td:eq(1) select').html();
            var trHtml = '<tr>';
            trHtml += '<td>Variação 1</td>';
            trHtml += '<td> <select name="StoreProductAttributes[' + index + ']" class="store-product-attributes">' + selectOptions + '</select> </td>';
            trHtml += '<td width="50%"><input class="form-control" name="StoreProductAttributeValues[' + index + '][]" placeholder="Escreva a variação e aperte enter para inserir"></td>';
            trHtml += '<td><a class="del-attribute"><i class="fa fa-times-circle"></i></a></td>';
            trHtml += '</tr>';
            $('#table-attributes tbody').append(trHtml);
            Store.reWriteOptionLabel();
        })
    },
    delAttribute: function() {
        $('body').on('click', '.del-attribute', function() {
            $(this).closest('tr').remove();
            Store.reWriteOptionLabel();
            Store.addSubproducts();
        });
    },
    reWriteOptionLabel: function() {
        if ($("#table-attributes tbody tr").length <= 0) {
            return false;
        }
        $('#table-attributes tbody tr').each(function(index, element) {
            $(element).find('td').first().html('Variação ' + (index + 1));
            // rewrite name and id for select attributes
            var secondTd = $(element).find('td').get(1);
            var selectAttributes = $(secondTd).find('select').first();
            $(selectAttributes).attr('name', 'StoreProductAttributes[' + index + ']');
            $(selectAttributes).attr('id', 'storeprodutcts-attributes-' + index + '-id');
            $('#storeprodutcts-attributes-' + index + '-id').select2({
                formatNoMatches: function(term) {
                    var idSource = 'storeprodutcts-attributes-' + index + '-id';
                    return "<div class='select2-result-label'><span class='select2-match'></span>" + term + " <span class='pull-right'><a href='javascript:void(0)' onClick=\"Store.sendAttribute('" + term + "', '" + idSource + "')\"><i class='fa fa-plus-circle'></i> adicionar</a></span></div>";
                },
                containerCss: {
                    'min-width': '200px'
                }
            });
            // rewrite name tagbox
            var thirdTd = $(element).find('td').get(2);
            var tags = $(thirdTd).find('input').first();
            $(tags).attr('id', 'storeprodutcts-attributes-' + index + '-value');
            // $(tags).attr('name', 'StoreProductAttributeValues[' + index + '][]');
            $(tags).removeAttr('name');
            $(tags).attr('textboxname', 'StoreProductAttributeValues[' + index + '][]');
            $(tags).attr('comboname', 'StoreProductAttributeValues[' + index + '][]');
            if ($(thirdTd).find('span').first().length <= 0) {
                $('#storeprodutcts-attributes-' + index + '-value').tagbox({
                    onChange: function(value) {
                        Store.addSubproducts();
                    },
                    onRemoveTag: function(value) {
                        Store.addSubproducts();
                    }
                });
            }
        });
    },
    sendAttribute(term, idSource) {
        Store.loading();
        $.post('/store/product-attributes/create', {
            'StoreProductAttributes[name]': term
        }, function(json) {
            $('#' + idSource).append("<option value=\"" + json.id + "\">" + json.name + "</option>");
            $('#' + idSource).val(json.id).trigger("change");
        }).done(function() {
            Store.removeLoading();
        });
    },
    addSubproducts() {
        Store.loading();
        $.post('/store/product/add-subproduct', $('#w0').serialize(), function(json) {

            var html = '';
            json.forEach(element => {
                console.log(element);
                var checked = "";
                if (element.form.checked || (!element.form.hasOwnProperty('checked') && $('#w0').attr('action').indexOf('update') < 0)) {
                    checked = 'checked';
                }
                html += '<tr>';
                html += '<td>';
                html += '<input type="checkbox" name="subproduct-checked[' + element.key + '][checkbox]" value="' + element.form.id + '" class="subproduct-checked" ' + checked + '>';
                element.values.forEach(value => {
                    html += '<input type="hidden" name="subproduct[' + element.key + '][product_attribute][]" value="' + value + '">';
                });
                html += '</td>';
                html += '<td>' + element.values.join(' , ') + '</td>';
                html += '<td><input type="text" name="subproduct[' + element.key + '][price]" class="form-control mask-currency" value="' + element.form.price + '"></td>';
                html += '<td><input type="text" name="subproduct[' + element.key + '][weight]" class="form-control mask-float-precision3" value="' + element.form.weight + '"></td>';
                html += '<td>';
                html += '<input type="text" name="subproduct[' + element.key + '][sku]" class="form-control subproduct-sku" value="' + element.form.sku + '">';
                html += '<input type="hidden" name="subproduct[' + element.key + '][id]" value="' + element.form.id + '" class="subproduct-id">';
                html += '</td>';
                html += '<td>';
                if (element.form.id == '') {
                    html += '<input type="text" name="subproduct[' + element.key + '][total_in_stock]" class="form-control mask-number" value="' + element.form.total_in_stock + '">';
                } else {
                    html += '<input type="text" value="' + element.form.total_in_stock + '" class="form-control"  disabled>';
                    html += '<input type="hidden" name="subproduct[' + element.key + '][total_in_stock]" value="' + element.form.total_in_stock + '">';
                }
                html += '</td>';
                html += '</tr>';
            })
            $('table#table-subproducts tbody').html(html);

            $('.mask-number').keypress(function(e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
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

        }).done(function() {
            Store.removeLoading();
        });
    },
    changeWithAttributes: function() {
        $('body').on('click', '#StoreProduct_with_attributes', function() {
            $('.with-attributes').hide();
            $('.without-attributes').show();
            $('#storeproducts-with_attributes').val(0);
            var checked = $(this).is(':checked');
            if (checked) {
                $('.with-attributes').show();
                $('.without-attributes').hide();
                $('#storeproducts-with_attributes').val(1);
            }
        })
    },
    submitForm: function() {
        var error = '';

        // validate subproducts
        if ($("#StoreProduct-with_attributes").is(":checked") || $('#table-subproducts tbody td').length > 0) {
            if ($("table#table-subproducts tbody tr").length <= 0) {
                error = "- Adicione ao menos uma variação. <br />";
            }

            if ($(".subproduct-checked:checked").length <= 0) {
                error += "- Você deve marcar ao menos um produto. <br />";
            }
            var variable = [];
            $('#table-attributes tbody select.store-product-attributes').each(function(index, element) {
                var valueAttr = $(element).val();
                if (variable.indexOf(valueAttr) >= 0) {
                    error += '- Não pode haver tipo de variação igual. <br />';
                    return false;
                }
                if (valueAttr != '') {
                    variable.push(valueAttr);
                }
            });

            // validate on frontend
            var sku = [];
            var postSku = [];
            $(".subproduct-sku").each(function(index, element) {
                var value = $(element).val();
                var tr = $(element).closest('tr').first();
                var td = $(tr).find('td').first();
                var checkbox = $(td).find('input[type="checkbox"]');

                if ($(checkbox).is(':checked')) {

                    if (sku.indexOf(value) >= 0) {
                        error += "- SKU " + value + " Já esta em uso em outro produto. <br />";
                        return false;
                    }
                    if (value != "") {
                        sku.push(value);
                    }

                    var id = null;
                    if ($(element).next().hasClass('subproduct-id')) {
                        id = $(element).next().val();
                    }
                    postSku.push({
                        id: id,
                        sku: $(element).val()
                    });

                    $.ajax({
                        async: false,
                        url: '/store/products/verify-sku',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            postSku: postSku
                        },
                        success: function(json) {
                            if (json.length > 0) {
                                error += '- SKU já esta sendo utilizado no(s) produto(s): <br />';
                            }
                            json.forEach(element => {
                                error += element.sku + ' - ' + element.name + ' <br />';
                            });
                        }
                    });

                }

            });

        }
        if (error != '') {

            $.smallBox({
                title: "Erro!",
                content: "<i class='fa fa-clock-o'></i> <i>" + error + "</i>",
                color: "#C46A69",
                iconSmall: "fa fa-times fa-2x fadeInRight animated",
                timeout: 4000
            });

            return false;
        }
        return true;

    },
    formValidate: function() {
        $('#w0').validate({
            submitHandler: function(form) {
                Page.submitForm();
            },
            rules: {
                'StoreProduct[name]': 'required',
                '#StoreProduct_store_product_category_id': 'required',
                'StoreProduct[store_product_brand_id]': 'required',
            },
            messages: {
                'StoreProduct[name]': 'Obrigatório',
                'StoreProduct[store_product_category_id]': 'Obrigatório',
                'StoreProduct[store_product_brand_id]': 'Obrigatório'
            },
            highlight: function(element) {
                $(element).removeClass('is-valid').addClass('is-invalid');
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            errorElement: 'span',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    }
}
$('document').ready(function() {
    Store.init();
})