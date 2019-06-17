Sale = {
    init: function() {
        Sale.searchItem();
        Sale.addProduct();
        Sale.delProduct();

        $('#Product_quant, #Product_unit_value, #Product_discount, #Product_discount_type').on('blur', function() {
            Sale.calculateTotalValue();
        });

        $('#Product_discount_type').on('change', function() {
            Sale.calculateTotalValue();
        });

    },

    templateProduct: function(data) {
        return data.html;
    },

    temaplateProductSelection: function(data) {
        return data.selection;
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
        quant = isNaN(quant) ? 0 : quant;
        quant = parseFloat(quant);

        var discountValue = $('#Product_discount').val();
        var discountType = $('#Product_discount_type').val();
        discountValue = discountValue.replace(".", "");
        discountValue = discountValue.replace(",", ".");
        discountValue = isNaN(discountValue) ? 0 : discountValue;
        discountValue = parseFloat(discountValue);

        var price = $('#Product_unit_value').val();
        price = price.replace(".", "");
        price = price.replace(",", ".");
        price = isNaN(price) ? 0 : price;
        price = parseFloat(price);

        var subtotal = price * quant;

        if (discountType == '%') {
            discountValue = discountValue / 100;
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

            var quant = $('#Product_quant').val();
            var discountValue = $('#Product_discount').val();
            var discountType = $('#Product_discount_type').val();
            var productName = $('#Product_name').val();
            var unitValue = $('#Product_unit_value').val();
            var total = $('#Product_total_value').val();
            var totalValue = total.replace(".", "");
            totalValue = totalValue.replace(",", ".");

            var html = "<tr id='" + id + "'>";
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
        labelTotal = sum.toString().replace(",", "");
        labelTotal = labelTotal.replace(".", ",");
        $('#total-value').text(labelTotal);
    }
}

$('document').ready(function() {
    Sale.init();
});