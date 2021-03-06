Sale = {
    init: function() {
        Sale.searchCustomer();
        Sale.searchItem();
        Sale.addProduct();
        Sale.updProduct();
        Sale.discountBlur();
        Sale.delProduct();
        Sale.submitFormSale();
        Sale.submitFormCustomer();
        Sale.clearCustomer();
        Sale.shortcuts();
        $('#Product_quant, #Product_unit_value, #Product_discount, #Product_discount_type').on('keyup', function() {
            Sale.calculateTotalValue();
        });
        $('#Product_discount_type').on('change', function() {
            Sale.calculateTotalValue();
        });
    },

    searchCustomer: function() {
        $("#Customer_search").select2({
            formatNoMatches: function(term) {
                return 'Nenhum cliente encontrado.';
            },
            formatSearching: function() {
                return 'Procurando...';
            },
            formatInputTooShort: function(term, minLength) {
                var caracteresRemaining = parseInt(minLength - term.length);
                var message = "Digite mais " + caracteresRemaining + " caracteres para iniciar a pesquisa.";
                if (caracteresRemaining <= 1) {
                    message = "Digite mais 1 caractere para iniciar a pequisa.";
                }
                return message;
            },
            placeholder: "Pesquise pelo nome ou razão social do cliente...",
            minimumInputLength: 3,
            ajax: {
                url: "/sale/search-customer",
                dataType: "json",
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        term: term,
                        page: page
                    };
                },
                results: function(data, page) {
                    var more = (page * 10) < data.total_count;
                    return {
                        results: data.items,
                        more: more
                    };
                }
            },
            formatResult: function(data) {
                return data.selection;
            },
            formatSelection: function(data) {
                return data.selection;
            },
            escapeMarkup: function(m) {
                return m;
            }
        }).on('change', function(e) {
            $('#Customer_id').val(e.added.data.id);
            $('#Customer_name').val(e.added.data.firstname + ' ' + e.added.data.lastname);
        });
    },

    searchItem: function() {
        $("#Sale_search").select2({
            formatNoMatches: function(term) {
                return 'Nenhum produto encontrado.';
            },
            formatSearching: function() {
                return 'Procurando...';
            },
            formatInputTooShort: function(term, minLength) {
                var caracteresRemaining = parseInt(minLength - term.length);
                var message = "Digite mais " + caracteresRemaining + " caracteres para iniciar a pesquisa.";
                if (caracteresRemaining <= 1) {
                    message = "Digite mais 1 caractere para iniciar a pequisa.";
                }
                return message;
            },
            placeholder: "Pesquise pelo nome ou código do produto...",
            minimumInputLength: 3,
            ajax: {
                url: "/sale/search-item",
                dataType: "json",
                quietMillis: 250,
                data: function(term, page) {
                    return {
                        term: term,
                        page: page
                    };
                },
                results: function(data, page) {
                    var more = (page * 10) < data.total_count;
                    return { results: data.items, more: more };
                }
            },
            formatResult: function(data) {
                return data.html;
            },
            formatSelection: function(data) {
                return data.selection;
            },
            escapeMarkup: function(m) {
                return m;
            }
        }).on('change', function(e) {
            var id = e.added.product.id;
            var code = e.added.product.sku;
            var quant = '1,000';
            var price = e.added.product.price;
            var totalValue = e.added.product.price;
            var productName = e.added.product.name;
            var unitMeasurement = e.added.product.unit_measurement;

            $('#Product_id').val(id);
            $('#Product_code').val(code);
            $('#Product_quant').val(quant);
            $('#Product_unit_value').val(price);
            $('#Product_total_value').val(totalValue);
            $('#Product_name').val(productName);
            $('#Product_unit_measurement').val(unitMeasurement);
        });
    },

    convertToNumber: function(str) {
        var newStr = str.replace(',', '.');
        if (isNaN(newStr)) {
            return 0;
        }
        return newStr;
    },

    calculateTotalValue: function() {
        var quant = $('#Product_quant').val();
        quant = quant.replace(".", "");
        quant = quant.replace(",", ".");
        quant = isNaN(quant) || quant == '' ? 0 : quant;
        quant = parseFloat(quant);

        var discountValue = $('#Product_discount').val();
        var discountType = $('#Product_discount_type').val();
        discountValue = discountValue.replace(".", "");
        discountValue = discountValue.replace(",", ".");
        discountValue = isNaN(discountValue) || discountValue == '' ? 0 : discountValue;
        discountValue = parseFloat(discountValue);

        var price = $('#Product_unit_value').val();
        price = price.replace(".", "");
        price = price.replace(",", ".");
        price = isNaN(price) || price == '' ? 0 : price;
        price = parseFloat(price);

        var subtotal = price * quant;

        if (discountType == '%') {
            discountValue = (subtotal / 100) * discountValue;
        } else {
            discountValue *= 1;
        }
        var total = (subtotal - discountValue).toFixed(2);
        var totalValue = total.toString().replace(",", "");
        totalValue = totalValue.replace(".", ",");
        $('#Product_total_value').val(totalValue);
    },

    discountBlur: function() {
        $('#Sale_discount_value').on('blur', function() {
            $('#table-items input:first').trigger('blur');
        });
        $('#Sale_discount_type').on('change', function() {
            $('#table-items input:first').trigger('blur');
        });
    },

    addTempProduct: function(product) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/sale/add-temp-item',
            type: 'post',
            dataType: 'json',
            data: product,
            success: function(json) {
                console.log(json)
            },
            beforeSend: function() {
                $.blockUI({
                    theme: false,
                    message: 'Aguarde',
                    baseZ: 999999,
                    css: {
                        top: '50%'
                    }
                });
            },
            complete: function() {
                $.unblockUI();
            }
        });
    },

    addProduct: function() {
        $('#form-add-product').on('submit', function(e) {
            e.preventDefault();
            var id = parseInt($('#Product_id').val());

            if (isNaN(id)) {
                swal("Oops...", "Você deve localizar um produto.", "error");
                return false;
            }
            if ($('#table-items #' + id).length > 0) {
                swal("Oops...", "Este produto já esta no carrinho.", "error");
                return false;
            }

            if (!Sale.formProductValidate()) {
                swal("Oops...", "Todos os campos devem ser preenchidos.", "error");
                return false;
            }

            var code = $('#Product_code').val();
            var quant = $('#Product_quant').val();
            var discountValue = $('#Product_discount').val();
            var discountType = $('#Product_discount_type').val();
            var productName = $('#Product_name').val();
            var unitValue = $('#Product_unit_value').val();
            var total = $('#Product_total_value').val();
            var unitMeasurement = $('#Product_unit_measurement').val();
            var totalValue = total.replace(".", "");
            totalValue = totalValue.replace(",", ".");

            var html = `
            <tr id="${id}">
                <td class="text-left">${productName}</td>
                <td><input type="text" name="SaleItem[${id}][unit_value]" value="${unitValue}" data-id="${id}" id="SaleItem_${id}_unit_value" class="col-sm-12  mask-float" /></td>
                <td><input type="text" name="SaleItem[${id}][quantity]" value="${quant}" data-id="${id}" id="SaleItem_${id}_quantity" class="col-sm-12 mask-float-precision3" /></td>
                <td>
                    <input type="text" name="SaleItem[${id}][discount_value]" data-id="${id}" id="SaleItem_${id}_discount_value" value="${discountValue}" class="col-sm-7 mask-float" />
                    <select name="SaleItem[${id}][discount_type]" data-id="${id}" id="SaleItem_${id}_discount_type">
                        <option value="R$" ${ discountType != '%' ? 'selected' : null } >R$</option>
                        <option value="%" ${ discountType == '%' ? 'selected' : null }>%</option>
                    </select>
                </td>
                <td id="SaleItem_${id}_label_total_value">${total}</td>
                <td>
                    <a href="javascript: void(0)" class="text-danger del-item" data-id="${id}"><i class="fa fa-times-circle"></i></a>
                    <input type="hidden" name="SaleItem[${id}][store_product_id]" value="${id}" class="Store_product_id" />
                    <input type="hidden" name="SaleItem[${id}][total_value]" id="SaleItem_${id}_total_value" value="${totalValue}" class="total-value-by-item" />
                </td>
            </tr>`;

            $('#table-items tbody').append(html);

            $('#form-add-product')[0].reset();
            $('#Product_id').val('');
            Form.masks();
            Sale.calcTotalPedido();

            $("#Sale_search").select2('val', '');
            $("#Sale_search").select2('open');

            var product = {
                id: id,
                __token: $('#Sale_token').val(),
                sale: {
                    token: $('#Sale_token').val(),
                    net_value: $('#Sale_net_value').val(),
                    customer_id: $('#Sale_customer_id').val(),
                    discount_value: $('#Sale_discount_value').val(),
                    discount_type: $('#Sale_discount_type').val(),
                    gross_value: $('#Sale_gross_value').val(),
                },
                item: {
                    id: id,
                    unit_value: unitValue,
                    discount_value: discountValue,
                    discount_type: discountType,
                    quantity: quant,
                    total_value: totalValue,
                },
            }

            Sale.addTempProduct(product);

        });
    },

    updProduct: function() {
        $('body').on('blur', '#table-items input, #table-items select', function(e) {
            var id = $(this).attr('data-id');

            var valorUnitario = Sale.convertToNumber($(`#SaleItem_${id}_unit_value`).val());
            var quantidade = Sale.convertToNumber($(`#SaleItem_${id}_quantity`).val());
            var valorDesconto = Sale.convertToNumber($(`#SaleItem_${id}_discount_value`).val());
            var valorOriginalDesconto = valorDesconto;
            var tipoDesconto = $(`#SaleItem_${id}_discount_type`).val();
            if (tipoDesconto == '%') {
                valorDesconto = (valorUnitario / 100) * valorDesconto;
            }
            var totalValue = (valorUnitario * quantidade) - valorDesconto;

            $(`#SaleItem_${id}_total_value`).val(totalValue);
            $(`#SaleItem_${id}_label_total_value`).html(totalValue.toFixed(2));
            Sale.calcTotalPedido();

            var product = {
                id: id,
                __token: $('#Sale_token').val(),
                sale: {
                    token: $('#Sale_token').val(),
                    net_value: $('#Sale_net_value').val(),
                    customer_id: $('#Sale_customer_id').val(),
                    discount_value: $('#Sale_discount_value').val(),
                    discount_type: $('#Sale_discount_type').val(),
                    gross_value: $('#Sale_gross_value').val(),
                },
                item: {
                    id: id,
                    unit_value: valorUnitario,
                    discount_value: valorOriginalDesconto,
                    discount_type: tipoDesconto,
                    quantity: quantidade,
                    total_value: totalValue
                },
            }

            Sale.tempDelProduct(product);
            Sale.addTempProduct(product);

        });
    },

    tempDelProduct: function(product) {


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/sale/del-temp-item',
            type: 'post',
            dataType: 'json',
            data: product,
            success: function(json) {
                console.log(json)
            },
            fail: function() {
                $.smallBox({
                    title: "Atenção!",
                    content: "<i class='fa fa-clock-o'></i> <i>Erro na tenttiva de excluir este produto de forma temporária.</i>",
                    color: "#C46A69",
                    iconSmall: "fa fa-times fa-2x fadeInRight animated",
                    timeout: 4000
                });
            },
            beforeSend: function() {
                $.blockUI({
                    theme: false,
                    message: 'Aguarde',
                    baseZ: 999999,
                    css: {
                        top: '50%'
                    }
                });
            },
            complete: function() {
                $.unblockUI();
            }
        });
/*
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post('/sale/del-temp-item', product, function(json) {
                console.log(json);
            })
            .fail(function() {
                $.smallBox({
                    title: "Atenção!",
                    content: "<i class='fa fa-clock-o'></i> <i>Erro na tenttiva de excluir este produto de forma temporária.</i>",
                    color: "#C46A69",
                    iconSmall: "fa fa-times fa-2x fadeInRight animated",
                    timeout: 4000
                });
            });*/
    },

    delProduct: function() {
        $('body').on('click', '.del-item', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $('#table-items tbody tr#' + id).remove();
            $("#Sale_search").focus();
            Sale.calcTotalPedido();

            var product = {
                id: id,
                __token: $('#Sale_token').val()
            }
            Sale.tempDelProduct(product);
        });
    },

    calcTotalPedido: function() {
        var grossValue = 0;
        $(".total-value-by-item").each(function() {
            grossValue += parseFloat(this.value);
        });

        var discountType = $('#Sale_discount_type').val();
        var discountValue = Sale.convertToNumber($('#Sale_discount_value').val());
        discountValue = isNaN(discountValue) ? 0 : discountValue;
        if (discountType == '%') {
            discountValue = (grossValue / 100) * discountValue;
        }
        var netValue = grossValue - discountValue;
        netValue = netValue.toFixed(2);
        labelTotal = netValue.toString().replace(",", "");
        labelTotal = labelTotal.replace(".", ",");

        $('#Sale_gross_value').val(grossValue);
        $('#Sale_net_value').val(netValue);
        $('#total-value').text(labelTotal);
    },

    submitFormSale: function() {
        $('#form-sale').on('submit', function(e) {
            if (!Sale.formSaleValidate()) {
                swal("Oops...", "Você deve incluir produto(s) no carrinho.", "error");
                return false;
            }
            return true;
        });
    },

    submitFormCustomer: function() {
        $('#form-customer').on('submit', function(e) {
            e.preventDefault();
            $('#Sale_customer_id').val($('#Customer_id').val());
            $('#Sale_customer_name').html($('#Customer_name').val());
            $('#Sale_type').val($('#Customer_type').val());
            $('#modal-customer').modal('hide');
            $('#btn-customer-clear').removeClass('d-none');
        });
    },

    clearCustomer: function() {
        $('#btn-customer-clear').on('click', function(e) {
            e.preventDefault();
            $('#Sale_customer_id').val('');
            $('#Sale_customer_name').html('AO CONSUMIDOR');
            $(this).addClass('d-none');
        });
    },

    formProductValidate: function() {
        var id = $('#Product_id').val().length;
        var quant = $('#Product_quant').val().length;
        var unitValue = $('#Product_unit_value').val().length;
        var totalValue = $('#Product_total_value').val().length;
        if (id <= 0 || quant <= 0 || unitValue <= 0 || totalValue <= 0) {
            return false;
        }
        return true;
    },

    formSaleValidate: function() {
        if ($('#table-items tbody tr').length <= 0) {
            return false;
        }
        return true;
    },

    shortcuts: function() {
        $("body").keydown(function(event) {
            if (event.which == 113) { //F2
                $("#Sale_search").select2('open');
                $("#Sale_search").trigger('click');
            }
            if (event.which == 114) { //F3
                $('#form-add-product').trigger('submit');
            }
            if (event.which == 115) { //F4
                $('#form-sale').trigger('submit');
            }
        });
    }

}

$('document').ready(function() {
    Sale.init();
});
