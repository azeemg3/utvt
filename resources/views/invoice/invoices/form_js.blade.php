<script type="text/javascript">
(function () {
    var products = @json($products);
    var rowIndex = {{ $existingItems->count() }};

    function productOptions(selectedId) {
        var html = '<option value="">Select Product</option>';
        products.forEach(function (p) {
            var selected = String(selectedId) === String(p.id) ? ' selected' : '';
            html += '<option value="' + p.id + '" data-price="' + p.price + '"' + selected + '>'
                + p.name + ' (' + parseFloat(p.price).toFixed(2) + ')</option>';
        });
        return html;
    }

    function addRow(item) {
        item = item || {};
        var idx = rowIndex++;
        var row = '<tr class="item-row">'
            + '<td><select name="items[' + idx + '][product_id]" class="form-control form-control-sm product-select" required>'
            + productOptions(item.product_id || '')
            + '</select></td>'
            + '<td><input type="number" step="0.01" min="0.01" name="items[' + idx + '][quantity]" class="form-control form-control-sm qty-input" value="' + (item.quantity || 1) + '" required></td>'
            + '<td><input type="number" step="0.01" min="0" name="items[' + idx + '][unit_price]" class="form-control form-control-sm price-input" value="' + (item.unit_price || 0) + '" required></td>'
            + '<td><input type="text" class="form-control form-control-sm line-total" value="0.00" readonly></td>'
            + '<td class="text-center"><button type="button" class="btn btn-xs btn-danger remove-line"><i class="fas fa-times"></i></button></td>'
            + '</tr>';
        $('#items-body').append(row);
        recalc();
    }

    function formatMoney(n) {
        return (Math.round((n + Number.EPSILON) * 100) / 100).toFixed(2);
    }

    function recalc() {
        var subtotal = 0;
        $('#items-body .item-row').each(function () {
            var qty = parseFloat($(this).find('.qty-input').val()) || 0;
            var price = parseFloat($(this).find('.price-input').val()) || 0;
            var line = qty * price;
            $(this).find('.line-total').val(formatMoney(line));
            subtotal += line;
        });

        var discount = parseFloat($('#discount').val()) || 0;
        var taxPercent = parseFloat($('#tax_percent').val()) || 0;
        var taxable = Math.max(subtotal - discount, 0);
        var tax = taxable * (taxPercent / 100);
        var total = taxable + tax;

        $('#subtotal-display').text(formatMoney(subtotal));
        $('#discount-display').text(formatMoney(discount));
        $('#tax-display').text(formatMoney(tax));
        $('#total-display').text(formatMoney(total));
    }

    $(document).ready(function () {
        if ($('#items-body .item-row').length === 0) {
            addRow();
        } else {
            recalc();
        }

        $('#add-line').on('click', function () {
            addRow();
        });

        $(document).on('click', '.remove-line', function () {
            if ($('#items-body .item-row').length <= 1) {
                Swal.fire({ icon: 'warning', title: 'At least one product is required' });
                return;
            }
            $(this).closest('tr').remove();
            recalc();
        });

        $(document).on('change', '.product-select', function () {
            var price = $(this).find(':selected').data('price');
            var $row = $(this).closest('tr');
            if (price !== undefined) {
                $row.find('.price-input').val(parseFloat(price).toFixed(2));
            }
            recalc();
        });

        $(document).on('input', '.qty-input, .price-input, #discount, #tax_percent', function () {
            recalc();
        });

        $('#invoice-form').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var action = $form.data('action');
            var method = $form.data('method') || 'POST';
            var formData = $form.serializeArray();

            if (method === 'PUT') {
                formData.push({ name: '_method', value: 'PUT' });
            }

            var $btn = $('#save-invoice');
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                type: 'POST',
                url: action,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $.param(formData),
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: res.message || 'Saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function () {
                        if (res.redirect) {
                            window.location.href = res.redirect;
                        } else {
                            window.location.href = "{{ route('invoice.index') }}";
                        }
                    });
                },
                error: function (xhr) {
                    $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Invoice');
                    var errors = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (i, v) {
                            errors += '&#187; ' + v + '<br>';
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errors = xhr.responseJSON.message;
                    } else {
                        errors = 'Something went wrong';
                    }
                    Swal.fire({ icon: 'error', title: 'Validation Error', html: errors });
                }
            });
        });

        $.ajax({
            url: "{{ route('product.list_active') }}",
            type: 'GET',
            success: function (data) {
                if (Array.isArray(data) && data.length) {
                    products = data;
                }
            }
        });
    });
})();
</script>
