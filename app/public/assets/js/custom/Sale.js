Sale = {
    init: function() {
        Sale.searchCustomer();
        Sale.searchItem();
        Sale.addProduct();
        Sale.delProduct();
        Sale.submitFormSale();
        Sale.submitFormCustomer();
        Sale.clearCustomer();
        $('#Product_quant, #Product_unit_value, #Product_discount, #Product_discount_type').on('blur', function() {
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

            $('#Product_id').val(id);
            $('#Product_code').val(code);
            $('#Product_quant').val(quant);
            $('#Product_unit_value').val(price);
            $('#Product_total_value').val(totalValue);
            $('#Product_name').val(productName);
        });
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
            var totalValue = total.replace(".", "");
            totalValue = totalValue.replace(",", ".");

            var html = "<tr id='" + id + "'>";
            html += "<td>" + code + "</td>";
            html += "<td>" + quant + "</td>";
            html += "<td>" + productName + "</td>";
            if (discountType == '%') {
                html += "<td>" + discountValue + "%</td>";
            } else {
                html += "<td>" + discountValue + "</td>";
            }
            html += "<td>" + total + "</td>";
            html += "<td>";
            html += "<a href='javascript: void(0)' class='text-danger del-item' data-id='" + id + "' ><i class='fa fa-times-circle'></i></a>";
            html += "<input type='hidden' name='SaleItem[" + id + "][store_product_id]' value='" + id + "' class='Store_product_id'>";
            html += "<input type='hidden' name='SaleItem[" + id + "][unit_value]' value='" + unitValue + "'>";
            html += "<input type='hidden' name='SaleItem[" + id + "][discount_value]' value='" + discountValue + "'>";
            html += "<input type='hidden' name='SaleItem[" + id + "][discount_type]' value='" + discountType + "'>";
            html += "<input type='hidden' name='SaleItem[" + id + "][quantity]' value='" + quant + "'>";
            html += "<input type='hidden' name='SaleItem[" + id + "][total_value]' value='" + totalValue + "' class='total-value-by-item'>";
            html += "</td>";
            html += "</tr>";

            $('#table-items tbody').append(html);

            $('#form-add-product')[0].reset();
            $('#Product_id').val('');
            Sale.calcTotalPedido();

            $("#Sale_search").select2('val', '');
            $("#Sale_search").select2('focus');
        });
    },

    delProduct: function() {
        $('body').on('click', '.del-item', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $('#table-items tbody tr#' + id).remove();
            $("#Sale_search").focus();
            Sale.calcTotalPedido();
        });
    },

    calcTotalPedido: function() {
        var sum = 0;
        $(".total-value-by-item").each(function() {
            sum += parseFloat(this.value);
        });
        $('#Sale_net_value').val(sum);
        labelTotal = sum.toString().replace(",", "");
        labelTotal = labelTotal.replace(".", ",");
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
    }

}

$('document').ready(function() {
    Sale.init();
});