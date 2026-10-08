/*
 * Sales Order (create / edit / view) page script.
 * Lives in public/js so editor "format on save" cannot break Blade syntax.
 * Server values come from #soJsConfig (data-* attributes) in create.blade.php.
 */
(function () {
    var el = document.getElementById('soJsConfig');
    if (!el) { return; }
    var d = el.dataset;
    window.SO_CFG = {
        urlSubcategories: d.urlSubcategories,
        urlProducts: d.urlProducts,
        urlAttrFromProduct: d.urlAttrFromProduct,
        urlPackSize: d.urlPackSize,
        urlAttributes: d.urlAttributes,
        urlDistrict: d.urlDistrict,
        urlPanchayath: d.urlPanchayath,
        urlNearest: d.urlNearest,
        urlExistingCustomer: d.urlExistingCustomer,
        csrf: d.csrf,
        viewmode: d.viewmode || 'off',
        customerCode: d.customerCode || '',
        hasPaymentImage: d.hasPaymentImage === 'true',
        isView: d.isView === 'true',
        nearestMaxKm: parseFloat(d.nearestMaxKm) || 50,
        franchises: JSON.parse(d.franchises || '[]'),
        categoryOptions: (document.getElementById('soCategoryOptions') || {}).innerHTML || ''
    };
})();
var CFG = window.SO_CFG || {};


    $(document).ready(function() {
        console.log('Sales Order JS loaded with Category & Attribute flow');

        let rowIndex = 0;
        $('#productTable tbody tr').each(function() {
            const m = ($(this).find('[name^="products["]').first().attr('name') || '').match(
                /^products\[(\d+)\]/);
            if (m) rowIndex = Math.max(rowIndex, parseInt(m[1], 10) + 1);
        });

        /*
        |--------------------------------------------------------------------------
        | Inject mobile card labels from thead (harmless on desktop)
        |--------------------------------------------------------------------------
        */
        function soLabelRows() {
            const labels = $('#productTable thead th').map(function() {
                return $(this).text().replace(/\*/g, '').trim();
            }).get();
            $('#productTable tbody tr').each(function() {
                $(this).children('td').each(function(i) {
                    $(this).attr('data-label', labels[i] || '');
                });
            });
        }
        soLabelRows();

        /*
        |--------------------------------------------------------------------------
        | Helper to Normalize Category Value to Catalog Key
        |--------------------------------------------------------------------------
        */
        function resolveCategoryKey(catVal) {
            if (!catVal) return '';
            let lower = String(catVal).toLowerCase().trim();
            if (lower.indexOf('organ') !== -1 || lower === '1') {
                return 'organics';
            }
            if (lower.indexOf('plant') !== -1 || lower.indexOf('garden') !== -1 || lower.indexOf('foliage') !==
                -1 || lower === '6') {
                return 'garden_plants';
            }
            return catVal;
        }

        /*
        |--------------------------------------------------------------------------
        | Add New Product Row
        |--------------------------------------------------------------------------
        */
        $('#addRow').on('click', function() {
            let row = `
            <tr class="new-product-row">
                <!-- 1. Category -->
                <td>

                    <select name="products[${rowIndex}][n_category_id]" class="form-select category-select mandatory" data-message="Please Select Category">
                        <option value="">Select Category First</option>
                            ${CFG.categoryOptions}
                    </select>
                </td>

                <!-- 2. Sub Category -->
                <td>
                    <select name="products[${rowIndex}][n_sub_category_id]" class="form-select subcategory-select" disabled>
                        <option value="">Select Sub Category </option>
                    </select>
                </td>

                <!-- 3. Product -->
                <td>
                    <select name="products[${rowIndex}][c_product_name]" class="form-select product-select mandatory" data-message="Please Select Product" disabled>
                        <option value="">Select Category First</option>
                    </select>
                </td>

                <!-- 4. Attribute / Pack Size -->
                <td>
                    <select name="products[${rowIndex}][c_unit]" class="form-select packSize-select" data-message="Please Select Pack Size" disabled>
                        <option value="">Select Product First</option>

                    </select>
                    <input type="hidden" class="n_product_id" name="products[${rowIndex}][product_id]" value=''>
                </td>

                <!-- 5. HSN Code -->
                <td>
                    <input type="text" name="products[${rowIndex}][c_hsn_code]" class="form-control c_hsn_code" value="" readonly>
                </td>

                <!-- 6. Price (Excl GST) -->
                <td>
                    <input type="text" name="products[${rowIndex}][product_price]" class="form-control price" value="0.00" readonly>
                </td>

                <!-- 7. Quantity -->
                <td>
                    <input type="number" name="products[${rowIndex}][qty]" class="form-control qty" value="1" min="1">
                </td>


                <!-- 9. Discount -->
                <td>
                    <input type="number" name="products[${rowIndex}][discount]" class="form-control discount" value="0.00" step="0.01" min="0">
                </td>

                <!-- 10. GST % -->
                <td>
                    <input type="number" name="products[${rowIndex}][n_gst_percentage]" class="form-control gst_percentage" value="0.00" step="0.01" readonly>
                </td>

                <!-- 11. GST Amount -->
                <td>
                    <input type="text" name="products[${rowIndex}][gst_amount]" class="form-control gst_amount" value="0.00" readonly>
                </td>

                <!-- 12. Discounted / Taxable Price -->
                <td>
                    <input type="text" name="products[${rowIndex}][discounted_price]" class="form-control discounted_price" value="0.00" readonly>
                </td>

                <!-- 13. Total (MRP) -->
                <td>
                    <input type="text" name="products[${rowIndex}][product_total]" class="form-control total" value="0.00" readonly>
                </td>

                <!-- 14. Action -->
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm removeRow">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;

            $('#productTable tbody').append(row);
            rowIndex++;
            soLabelRows();
        });


        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | CATEGORY CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on('change', '.category-select', function() {

                let row = $(this).closest('.new-product-row');

                let categoryId = $(this).val();

                let subCategory = row.find('.subcategory-select');

                let product = row.find('.product-select');
                let packSize = row.find('.packSize-select');

                // Reset dependent dropdowns
                subCategory.html(
                    '<option value="">Select Category First</option>'
                );

                product.html(
                    '<option value="">Select Sub Category First</option>'
                );

                packSize.html(
                    '<option value="">Select Product First</option>'
                );

                if (!categoryId) {
                    return;
                }


                let url =
                    CFG.urlSubcategories;
                url = url.replace(':categoryId', categoryId);

                $.ajax({
                    url: url,
                    type: 'GET',

                    success: function(data) {



                        subCategory.empty();

                        subCategory.append(
                            $('<option>', {
                                value: '',
                                text: 'Select Sub Category',

                            })
                        );

                        $.each(data.subcategories, function(index, item) {

                            subCategory.append(
                                $('<option>', {
                                    value: item.n_category_id,
                                    text: item.c_category_name,

                                })
                            );
                            // Enable subcategory dropdown
                            subCategory.prop('disabled', false);
                        });

                    },

                    error: function(xhr) {
                        console.log('Sub Category Error:', xhr.responseText);
                    }
                });
            });


            /*
            |--------------------------------------------------------------------------
            | SUB CATEGORY CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on('change', '.subcategory-select', function() {

                let row = $(this).closest('.new-product-row');

                let subCategoryId = $(this).val();

                let product = row.find('.product-select');
                let packSize = row.find('.packSize-select');

                // Reset product and attribute
                product.html(
                    '<option value="">Select Product</option>'
                );

                packSize.html(
                    '<option value="">Select Product First</option>'
                );

                if (!subCategoryId) {
                    return;
                }

                let url =
                    CFG.urlProducts;
                url = url.replace(':subCategoryId', subCategoryId);

                $.ajax({

                    url: url,
                    type: 'GET',

                    success: function(data) {
                        product.empty();

                        product.append(
                            $('<option>', {
                                value: '',
                                text: 'Select Product',

                            })
                        );

                        $.each(data.products, function(index, item) {

                            product.append(
                                $('<option>', {
                                    value: item.n_product_id,
                                    text: item.c_product_name
                                })
                            );

                            // Enable product dropdown
                            product.prop('disabled', false);

                        });

                    },

                    error: function(xhr) {
                        console.log('Product Error:', xhr.responseText);
                    }

                });

            });


            /*
            |--------------------------------------------------------------------------
            | PRODUCT CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on('change', '.product-select', function() {


                let row = $(this).closest('.new-product-row');
                let categoryCode = row.find(".category-select").find(':selected').attr(
                    "data-categoryCode");
                let productId = $(this).val();

                let productName = $(this).find(':selected').text();

                let packSize = row.find('.packSize-select');

                packSize.html(
                    '<option value="">Select Attribute / Pack Size</option>'
                );

                if (!productId) {
                    return;
                }

                if (String(categoryCode).toUpperCase() === "PLANTS") {

                    // Plant products have no pack size / attribute -> not applicable
                    packSize.html('<option value="">Not applicable</option>')
                        .val('')
                        .prop('disabled', true);

                    let url =
                        CFG.urlAttrFromProduct;
                    url = url.replace(':productId', productId);

                    $.ajax({

                        url: url,
                        type: 'GET',

                        success: function(data) {

                            // Example:
                            // Set HSN
                            row.find('.c_hsn_code').val(data.c_hsn_code);
                            //set gst percentage
                            row.find('.gst_percentage').val(data.n_gst_percentage);
                            row.find('.n_product_id').val(data.n_product_id);

                            globalmrp = data.n_mrp;
                            globalGstPercentage = data.n_gst_percentage

                            calculateRowWithMrp(row, data.n_mrp, data
                                .n_gst_percentage);

                        },

                        error: function(xhr) {
                            console.log(
                                'Attribute Details Error:',
                                xhr.responseText
                            );
                        }

                    });

                } else {

                    let url =
                        CFG.urlPackSize;
                    url = url.replace(':productName', productName);

                    $.ajax({

                        url: url,
                        type: 'GET',

                        success: function(data) {
                            packSize.empty();

                            packSize.append(
                                $('<option>', {
                                    value: '',
                                    text: 'Select Pack Size',
                                })
                            );
                            $.each(data.units, function(index, item) {

                                packSize.append(
                                    $('<option>', {
                                        value: item.c_unit,
                                        text: item.c_unit
                                    })
                                );

                                // Enable packSize dropdown
                                packSize.prop('disabled', false);

                            });

                        },

                        error: function(xhr) {
                            console.log(
                                'Pack Size Error:',
                                xhr.responseText
                            );
                        }

                    });
                }

            });


            /*
            |--------------------------------------------------------------------------
            | ATTRIBUTE / PACK SIZE CHANGE
            |--------------------------------------------------------------------------
            */

            let globalmrp = 0;
            let globalGstPercentage = '';

            $(document).on('change', '.packSize-select', function() {

                let row = $(this).closest('.new-product-row');
                let productId = row.find(".product-select").find(':selected').val();
                let productName = row.find(".product-select").find(':selected').text();
                let packSize = $(this).val();

                if (!packSize) {
                    return;
                }

                let url =
                    CFG.urlAttributes;
                url = url.replace(':productName', productName);
                url = url.replace(':packSize', packSize);

                $.ajax({

                    url: url,
                    type: 'GET',

                    success: function(data) {

                        // Example:
                        // Set HSN
                        row.find('.c_hsn_code').val(data.c_hsn_code);
                        //set gst percentage
                        row.find('.gst_percentage').val(data.n_gst_percentage);
                        row.find('.n_product_id').val(data.n_product_id);

                        globalmrp = data.n_mrp;
                        globalGstPercentage = data.n_gst_percentage

                        calculateRowWithMrp(row, data.n_mrp, data.n_gst_percentage);

                    },

                    error: function(xhr) {
                        console.log(
                            'Attribute Details Error:',
                            xhr.responseText
                        );
                    }

                });

            });

            /*
            |--------------------------------------------------------------------------
            | Quantity / Discount Change Event
            |--------------------------------------------------------------------------
            */

            $(document).on('input change', '.qty, .discount', function() {

                let row = $(this).closest('.new-product-row');

                calculateRowWithMrp(
                    row,
                    parseFloat(globalmrp) || 0,
                    parseFloat(globalGstPercentage) || 0
                );
                if ($(this).closest('.existing-product-row').length) {
                    calculateExistingRow($(this).closest('.existing-product-row'));
                    calculateSummary();
                }

            });
            /* $(document).on('input', '.qty, .discount', function () {

                const input = this;
                const row = $(input).closest('.new-product-row');

                clearTimeout(row.data('calculationTimer'));

                const timer = setTimeout(function () {

                    calculateRowWithMrp(
                        row,
                        parseFloat(globalmrp) || 0,
                        parseFloat(globalGstPercentage) || 0
                    );

                }, 200);

                row.data('calculationTimer', timer);
            });


            $(document).on('change', '.qty, .discount', function () {

                const row = $(this).closest('.new-product-row');

                clearTimeout(row.data('calculationTimer'));

                calculateRowWithMrp(
                    row,
                    parseFloat(globalmrp) || 0,
                    parseFloat(globalGstPercentage) || 0
                );

            }); */
        });
        // /*
        // |--------------------------------------------------------------------------
        // | Subcategory Selection Change
        // |--------------------------------------------------------------------------
        // */
        // $(document).on('change', '.subcategory-select', function () {
        //     let row = $(this).closest('tr');
        //     let catKey = resolveCategoryKey(row.find('.category-select').val());
        //     let subCatVal = $(this).val();

        //     let productSelect = row.find('.product-select');
        //     let attrSelect = row.find('.attribute-select');

        //     productSelect.empty();
        //     attrSelect.empty().prop('disabled', true);
        //     clearRowPricing(row);

        //     if (!catKey || !subCatVal || subCatVal === 'NA') {
        //         productSelect.html('<option value="">Select Sub Category First</option>').prop('disabled', true);
        //         return;
        //     }

        //     let catData = productCatalog[catKey];
        //     if (catData && catData.subcategories && catData.subcategories[subCatVal]) {
        //         productSelect.prop('disabled', false);
        //         productSelect.append('<option value="">Select Product</option>');

        //         catData.subcategories[subCatVal].forEach(function (p) {
        //             let opt = $(`<option value="${p.id}">${p.name} ${p.code ? '(' + p.code + ')' : ''}</option>`);
        //             opt.data('product-info', p);
        //             productSelect.append(opt);
        //         });
        //     }
        // });

        // /*
        // |--------------------------------------------------------------------------
        // | Product Selection Change -> Populates Attributes (Pack Sizes / Variants)
        // |--------------------------------------------------------------------------
        // */
        // $(document).on('change', '.product-select', function () {
        //     let row = $(this).closest('tr');
        //     let selectedOption = $(this).find(':selected');
        //     let productInfo = selectedOption.data('product-info');

        //     let attrSelect = row.find('.attribute-select');
        //     attrSelect.empty();
        //     clearRowPricing(row);

        //     if (!productInfo || !productInfo.attributes || productInfo.attributes.length === 0) {
        //         attrSelect.html('<option value="">No Attributes Available</option>').prop('disabled', true);
        //         return;
        //     }

        //     attrSelect.prop('disabled', false);

        //     if (productInfo.attributes.length > 1) {
        //         attrSelect.append('<option value="">Select Pack Size / Attribute</option>');
        //     }

        //     productInfo.attributes.forEach(function (attr) {
        //         let opt = $(`<option value="${attr.name}">${attr.name} - ₹${attr.mrp.toFixed(2)}</option>`);
        //         opt.attr('data-price', attr.mrp);
        //         opt.attr('data-unit', attr.unit || '');
        //         opt.attr('data-gst', productInfo.gst || 0);
        //         opt.attr('data-hsn-code', productInfo.hsn || '');
        //         attrSelect.append(opt);
        //     });

        //     // Automatically select if single attribute (e.g., plants or 1 NOS)
        //     if (productInfo.attributes.length === 1) {
        //         attrSelect.val(productInfo.attributes[0].name).trigger('change');
        //     }
        // });

        // /*
        // |--------------------------------------------------------------------------
        // | Attribute Selection Change -> Calculates Pricing and Totals
        // |--------------------------------------------------------------------------
        // */
        // $(document).on('change', '.attribute-select', function () {
        //     let row = $(this).closest('tr');
        //     let selectedOption = $(this).find(':selected');

        //     if (!selectedOption.val()) {
        //         clearRowPricing(row);
        //         calculateSummary();
        //         return;
        //     }

        //     let mrp = parseFloat(selectedOption.attr('data-price')) || 0;
        //     let gstPercentage = parseFloat(selectedOption.attr('data-gst')) || 0;
        //     let hsnCode = selectedOption.attr('data-hsn-code') || '';
        //     let unit = selectedOption.attr('data-unit') || '';

        //     row.find('.c_hsn_code').val(hsnCode);
        //     row.find('.c_unit').val(unit);
        //     row.find('.gst_percentage').val(gstPercentage.toFixed(2));

        //     calculateRowWithMrp(row, mrp, gstPercentage);
        // });


        /*
        |--------------------------------------------------------------------------
        | Calculation Formula (MRP Includes GST)
        |--------------------------------------------------------------------------
        */
        function calculateRowWithMrp(row, mrp, gstPercentage) {

            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;

            if (qty < 0) qty = 0;
            if (discount < 0) discount = 0;

            let price = 0;
            let grossAmount = 0;
            let taxableAmount = 0;
            let gstAmount = 0;
            let lineTotal = 0;

            if (mrp > 0) {
                // Exclusive price
                price = mrp / (1 + (gstPercentage / 100));

                // Price × Quantity
                grossAmount = price * qty;

                // Taxable Amount
                taxableAmount = grossAmount - discount;
                if (taxableAmount < 0) taxableAmount = 0;

                // GST Amount
                gstAmount = (taxableAmount * gstPercentage) / 100;

                // Line Total
                lineTotal = taxableAmount + gstAmount;
            }

            row.find('.price').val(price.toFixed(2));
            row.find('.gst_amount').val(gstAmount.toFixed(2));
            row.find('.discounted_price').val(taxableAmount.toFixed(2));
            row.find('.total').val(lineTotal.toFixed(2));

            calculateSummary();
        }

        function calculateExistingRow(row) {
            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;
            let gstPercentage = parseFloat(row.find('.gst_percentage').val()) || 0;

            if (qty < 0) qty = 0;
            if (discount < 0) discount = 0;

            let grossAmount = price * qty;
            let taxableAmount = grossAmount - discount;
            if (taxableAmount < 0) taxableAmount = 0;

            let gstAmount = (taxableAmount * gstPercentage) / 100;
            let lineTotal = taxableAmount + gstAmount;

            row.find('.gst_amount').val(gstAmount.toFixed(2));
            row.find('.discounted_price').val(taxableAmount.toFixed(2));
            row.find('.total').val(lineTotal.toFixed(2));
        }

        function clearRowPricing(row) {
            row.find('.c_hsn_code').val('');
            row.find('.price').val('0.00');
            row.find('.c_unit').val('');
            row.find('.discount').val('0.00');
            row.find('.gst_percentage').val('0.00');
            row.find('.gst_amount').val('0.00');
            row.find('.discounted_price').val('0.00');
            row.find('.total').val('0.00');
        }

        /*
        |--------------------------------------------------------------------------
        | Summary Totals
        |--------------------------------------------------------------------------
        */
        /*  function calculateSummary() {
             let totalSales = 0;
             let totalDiscount = 0;
             let totalTaxable = 0;
             let totalGst = 0;

             $('#productTable tbody tr').each(function () {
                 let row = $(this);
                 let price = parseFloat(row.find('.price').val()) || 0;
                 let qty = parseFloat(row.find('.qty').val()) || 0;
                 let discount = parseFloat(row.find('.discount').val()) || 0;
                 let gstAmount = parseFloat(row.find('.gst_amount').val()) || 0;
                 let taxable = parseFloat(row.find('.discounted_price').val()) || 0;

                 let gross = price * qty;

                 totalSales += gross;
                 totalDiscount += discount;
                 totalTaxable += taxable;
                 totalGst += gstAmount;
             });

             let netSalesAmount = totalTaxable + totalGst;

             $('#summaryTotalSales').val(totalSales.toFixed(2));
             $('#summaryTotalDiscount').val(totalDiscount.toFixed(2));
             $('#summaryGstAmount').val(totalGst.toFixed(2));
             $('#summaryNetSales').val(netSalesAmount.toFixed(2));
             $('#n_amount_to_pay').val(netSalesAmount.toFixed(2));
         } */

        function calculateSummary() {
            let totalSales = 0;
            let totalDiscount = 0;
            let totalTaxable = 0;
            let totalGst = 0;

            $('#productTable tbody tr').each(function() {

                let row = $(this);

                let price = parseFloat(row.find('.price').val()) || 0;
                let qty = parseFloat(row.find('.qty').val()) || 0;
                let discount = parseFloat(row.find('.discount').val()) || 0;
                let gstAmount = parseFloat(row.find('.gst_amount').val()) || 0;

                let gross = price * qty;

                totalSales += gross;
                totalDiscount += discount;
                totalGst += gstAmount;

                // Taxable amount after discount
                totalTaxable += Math.max(gross - discount, 0);
            });

            // Net = Taxable + GST
            let netSalesAmount = totalTaxable + totalGst;

            $('#summaryTotalSales').val(totalSales.toFixed(2));
            $('#summaryTotalDiscount').val(totalDiscount.toFixed(2));
            $('#summaryGstAmount').val(totalGst.toFixed(2));
            $('#summaryNetSales').val(netSalesAmount.toFixed(2));
            $('#n_amount_to_pay').val(netSalesAmount.toFixed(2));
        }

        /*
        |--------------------------------------------------------------------------
        | Remove Product Row
        |--------------------------------------------------------------------------
        */
        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
            calculateSummary();
        });

        /*
        |--------------------------------------------------------------------------
        | Payment Mode Handling
        |--------------------------------------------------------------------------
        */
        $('.mode_of_payment').on('change', function() {
            handlePaymentMode();
        });

        const hasStoredPaymentImage = CFG.hasPaymentImage;

        function handlePaymentMode() {
            let paymentMode = $('.mode_of_payment:checked').val();
            // A stored proof is kept unless the user removed it
            const needsProofFile = !hasStoredPaymentImage || $('#remove_payment_image').val() === '1';

            if (!paymentMode) {
                $('#paymet-proofs').hide();
                $('#ps').show();
                $('#franchise-details').show();
                $('#c_transaction_id').removeClass('mandatory');
                $('#payment_image').removeClass('mandatory');
                return;
            }

            if (paymentMode === 'Paid to Franchise' || paymentMode === 'Cash on Delivery') {
                $('#paymet-proofs').hide();
                $('#ps').show();
                $('#franchise-details').show();
                $('#c_transaction_id').removeClass('mandatory');
                $('#payment_image').removeClass('mandatory');
            } else {
                $('#paymet-proofs').show();
                $('#ps').show();
                $('#franchise-details').show();
                $('#c_transaction_id').addClass('mandatory');
                $('#payment_image').toggleClass('mandatory', needsProofFile);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Franchise Location Cascading (State -> District -> Panchayath -> Store)
        |--------------------------------------------------------------------------
        */
        $('#franchise_state').on('change', function() {
            let stateId = $(this).val();

            $('#franchise_district').html('<option value="">Loading...</option>');
            $('#franchise_panchayath').html('<option value="">Select Panchayath</option>');
            $('#franchise').html('<option value="">Select Franchise</option>');

            if (!stateId) {
                $('#franchise_district').html('<option value="">Select District</option>');
                return;
            }

            $.ajax({
                type: 'GET',
                url: CFG.urlDistrict,
                data: {
                    state: stateId
                },
                dataType: 'json',
                success: function(response) {
                    $('#franchise_district').html(
                        '<option value="">Select District</option>');
                    if (response.districts) {
                        $.each(response.districts, function(index, district) {
                            $('#franchise_district').append(
                                `<option value="${district.id}">${district.district_name}</option>`
                            );
                        });
                    }
                },
                error: function() {
                    $('#franchise_district').html(
                        '<option value="">Unable to load districts</option>');
                }
            });
        });

        $('#franchise_district').on('change', function() {
            let districtId = $(this).val();

            $('#franchise_panchayath').html('<option value="">Loading...</option>');
            $('#franchise').html('<option value="">Select Franchise</option>');

            if (!districtId) {
                $('#franchise_panchayath').html('<option value="">Select Panchayath</option>');
                return;
            }

            $.ajax({
                type: 'GET',
                url: CFG.urlPanchayath,
                data: {
                    district: districtId
                },
                dataType: 'json',
                success: function(response) {
                    $('#franchise_panchayath').html(
                        '<option value="">Select Panchayath</option>');
                    if (response.panchayaths && response.panchayaths.length > 0) {
                        $.each(response.panchayaths, function(index, panchayat) {
                            $('#franchise_panchayath').append(
                                `<option value="${panchayat.id}">${panchayat.panchayath_name}</option>`
                            );
                        });
                    } else {
                        $('#franchise_panchayath').html(
                            '<option value="">No Panchayaths Found</option>');
                    }
                },
                error: function() {
                    $('#franchise_panchayath').html(
                        '<option value="">Unable to load Panchayaths</option>');
                }
            });
        });

        $('#franchise_panchayath').on('change', function() {
            const panchayathId = $(this).val();
            if (!panchayathId) {
                $('#franchise').html('<option value="">Select Franchise</option>');
                return;
            }
            findNearestFranchise(panchayathId);
        });

        function findNearestFranchise(panchayathId) {
            $('#franchise').html('<option value="">Finding franchise...</option>');

            fetch(CFG.urlNearest, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CFG.csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        panchayath_id: panchayathId
                    })
                })
                .then(res => res.json())
                .then(function(data) {
                    $('#franchise').html('<option value="">Select Franchise</option>');
                    if (!data.success) {
                        $('#franchise').html('<option value="">No Franchise Found</option>');
                        return;
                    }

                    let franchises = Array.isArray(data.franchises) ? data.franchises : (data.franchises ? [
                        data.franchises
                    ] : []);
                    if (franchises.length === 0) {
                        $('#franchise').html('<option value="">No Franchise Found</option>');
                        return;
                    }

                    franchises.forEach(function(f) {
                        $('#franchise').append(
                            `<option value="${f.n_store_id}">${f.c_store_name} ${f.c_store_code ? '(' + f.c_store_code + ')' : ''}</option>`
                        );
                    });

                    $('#franchise').val(franchises[0].n_store_id);
                })
                .catch(function() {
                    $('#franchise').html('<option value="">Unable to find franchise</option>');
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Order Type (Company vs Franchise)
        |--------------------------------------------------------------------------
        */
        function toggleOrderType() {
            const orderType = $('input[name="order_type"]:checked').val();
            if (orderType === 'franchise') {
                $('#franchise-location-details').show();
                $('#franchise_state, #franchise_district, #franchise_panchayath, #franchise').addClass(
                    'mandatory');
            } else if (orderType === 'company') {
                $('#franchise-location-details').hide();
                $('#franchise_state, #franchise_district, #franchise_panchayath, #franchise').removeClass(
                    'mandatory');
            }
        }

        $('input[name="order_type"]').on('change', toggleOrderType);

        /*
        |--------------------------------------------------------------------------
        | Image Upload Preview Helper
        |--------------------------------------------------------------------------
        */
        function setupImageUpload(inputId, previewId, containerId, removeInputId, removeButtonId) {
            $(document).on('change', '#' + inputId, function(event) {
                const file = event.target.files[0];
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file.');
                    $(this).val('');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + previewId).attr('src', e.target.result).show();
                    $('#' + removeInputId).val('0');

                    if ($('#' + removeButtonId).length === 0) {
                        $('#' + containerId).append(
                            `<br><button type="button" id="${removeButtonId}" class="btn btn-danger btn-sm mt-2">Remove Image</button>`
                        );
                    } else {
                        $('#' + removeButtonId).show();
                    }
                };
                reader.readAsDataURL(file);
            });

            $(document).on('click', '#' + removeButtonId, function() {
                $('#' + inputId).val('');
                $('#' + previewId).attr('src', '').hide();
                $('#' + removeInputId).val('1');
                $(this).hide();
                if (typeof handlePaymentMode === 'function') handlePaymentMode();
            });
        }

        setupImageUpload('payment_image', 'payment_image_preview', 'payment_preview_container',
            'remove_payment_image', 'remove_payment_image_btn');
        // Booklet proof: preview only (replace by choosing another file); it cannot be removed
        $(document).on('change', '#booklet_image', function(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                $(this).val('');
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#booklet_image_preview').attr('src', e.target.result);
                $('#booklet_image_link, #booklet_image_view_link').attr('href', e.target.result);
                $('#booklet_image_link, #booklet_image_view_text').show();
            };
            reader.readAsDataURL(file);
        });

        /*
    |--------------------------------------------------------------------------
    | View Mode
    |--------------------------------------------------------------------------
    */
        var viewmode = CFG.viewmode;
        if (viewmode === 'on') {
            $('#frm_create input:not([type="hidden"]):not([type="button"]):not([type="submit"])').prop(
                'readonly', true);
            $('#frm_create textarea').prop('readonly', true);
            $('#frm_create select, #frm_create input[type="radio"], #frm_create input[type="checkbox"], #frm_create input[type="file"], #addRow, .removeRow')
                .prop('disabled', true);
        }

        // Initialize Page
        calculateSummary();
        toggleOrderType();
        handlePaymentMode();
    });

    /*
    |--------------------------------------------------------------------------
    | Customer Toggle & Mobile Lookup
    |--------------------------------------------------------------------------
    */
    document.addEventListener('DOMContentLoaded', function() {
        const lookupCard = document.getElementById('lookupCard');
        const newCustomer = document.getElementById('newCustomer');
        const existingCustomer = document.getElementById('existingCustomer');

        function toggleCustomerType() {
            const selected = document.querySelector('input[name="c_customer_type"]:checked');
            if (!selected || !lookupCard) return;

            if (selected.value === 'existing') {
                lookupCard.classList.remove('d-none');
            } else {
                lookupCard.classList.add('d-none');
                $("#c_customer_code").val(
                    CFG.customerCode);
            }
        }

        if (newCustomer) newCustomer.addEventListener('change', toggleCustomerType);
        if (existingCustomer) existingCustomer.addEventListener('change', toggleCustomerType);
        toggleCustomerType();

        const lookupBtn = document.getElementById('lookupBtn');
        if (lookupBtn) {
            lookupBtn.addEventListener('click', function() {
                const mobile = document.getElementById('lookupMobile').value.trim();
                if (!/^[0-9]{10}$/.test(mobile)) {
                    alert('Please enter a valid 10-digit mobile number.');
                    return;
                }

                fetch(CFG.urlExistingCustomer, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": CFG.csrf
                        },
                        body: JSON.stringify({
                            mobile: mobile
                        })
                    })
                    .then(res => res.json())
                    .then(function(data) {
                        if (data.status === true && data.customer) {
                            $("#n_customer_id").val(data.customer.n_customer_id);
                            $("#c_customer_code").val(data.customer.c_customer_code);
                            $(".c_customer_name").val(data.customer.c_customer_name);
                            $('[name="n_whatsapp"]').val(data.customer.n_whatsapp || '');
                            $('[name="n_mobile"]').val(data.customer.n_mobile || '');
                            $('[name="c_email"]').val(data.customer.c_email || '');
                            $('[name="c_address"]').val(data.customer.c_address || '');
                            $('[name="c_pincode"]').val(data.customer.c_pincode || '');

                            if (data.customer.n_state_id) {
                                $('#n_state_id').val(data.customer.n_state_id);
                                districtFilter(data.customer.n_state_id, data.customer
                                    .n_district_id);
                            }
                            $('#lookupMessage').text('Customer loaded successfully!');
                        } else {
                            alert('Customer not found.');
                        }
                    })
                    .catch(function() {
                        alert('Unable to find customer. Please try again.');
                    });
            });
        }

        function districtFilter(state, selectedDistrict = null) {
            if (!state) return;
            $.ajax({
                type: 'GET',
                url: CFG.urlDistrict,
                data: {
                    state: state
                },
                dataType: 'json',
                success: function(data) {
                    $('#n_district_id').empty().append('<option value="">Select District</option>');
                    if (data.districts) {
                        $.each(data.districts, function(index, d) {
                            $('#n_district_id').append(
                                `<option value="${d.id}">${d.district_name}</option>`);
                        });
                        if (selectedDistrict) {
                            $('#n_district_id').val(selectedDistrict);
                        }
                    }
                }
            });
        }
    });
    
;

    /*
    |--------------------------------------------------------------------------
    | Order location: address -> latitude / longitude (OpenStreetMap Nominatim)
    | Same approach as Franchise > Add: try the most specific address first and
    | fall back to broader areas; the user confirms / adjusts the pin on a map.
    |--------------------------------------------------------------------------
    */
    // Payment-mode + Order-type visibility (self-contained, works for Add / Edit / View Details)
    $(function() {
        const STORED_PAYMENT_IMAGE = CFG.hasPaymentImage;
        const READONLY = CFG.isView;

        function applyPaymentVisibility() {
            const mode = $('input[name="c_mode_of_payment"]:checked').val();
            const needsProof = (mode === 'UPI' || mode === 'Bank Deposit'); // Amount, Transaction ID, Proof
            $('#paymet-proofs').toggle(needsProof);
            $('#c_transaction_id').toggleClass('mandatory', needsProof);
            const needFile = needsProof && (!STORED_PAYMENT_IMAGE || $('#remove_payment_image').val() === '1');
            $('#payment_image').toggleClass('mandatory', needFile);
            // Hidden fields are not submitted (cash / paid-to-franchise sales carry no proof)
            if (!READONLY) {
                $('#paymet-proofs').find('input').prop('disabled', !needsProof);
                // the amount is display-only; keep it readonly
                $('#n_amount_to_pay').prop('readonly', true);
            }
        }

        function applyOrderTypeVisibility() {
            const $radios = $('input[name="order_type"]');
            if (!$radios.length) {
                return;
            } // roles without Order Type always see the franchise
            const type = $radios.filter(':checked').val();
            const show = (type === 'franchise');
            $('#franchise-location-details').toggle(show);
            $('#franchise').toggleClass('mandatory', show);
            if (!READONLY) {
                $('#franchise').prop('disabled', !show);
            }
        }

        $(document).on('change', 'input[name="c_mode_of_payment"]', applyPaymentVisibility);
        $(document).on('change', 'input[name="order_type"]', applyOrderTypeVisibility);
        $(document).on('click', '#remove_payment_image_btn', function() {
            setTimeout(applyPaymentVisibility, 0);
        });
        applyPaymentVisibility();
        applyOrderTypeVisibility();
    });

    $(function() {
        const $lat = $('#so_latitude'),
            $lng = $('#so_longitude'),
            $status = $('#soLocationStatus');
        const $btn = $('#soGetLocationBtn'),
            $mapBox = $('#soLocationMap');
        const BTN_HTML = '<i class="ti ti-map-pin-search"></i> Get Location from Address';
        const SO_VIEW = CFG.isView; // View Details = read-only
        let map = null,
            marker = null;

        function say(type, html) {
            $status.removeClass('text-muted text-success text-danger text-warning')
                .addClass('text-' + type).html(html);
        }

        function validCoords(lat, lng) {
            return isFinite(lat) && isFinite(lng) && Math.abs(lat) <= 90 && Math.abs(lng) <= 180 &&
                $lat.val().toString().trim() !== '' && $lng.val().toString().trim() !== '';
        }

        function ensureMap(lat, lng, zoom) {
            $mapBox.show();
            if (!map) {
                map = L.map('soLocationMap').setView([lat, lng], zoom);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                map.on('click', function(e) {
                    if (SO_VIEW) {
                        return;
                    }
                    setLocation(e.latlng.lat, e.latlng.lng, 'manual');
                });
            } else {
                map.setView([lat, lng], zoom);
            }
            setTimeout(function() {
                map.invalidateSize();
            }, 250);
        }

        function placeMarker(lat, lng) {
            if (marker) {
                marker.setLatLng([lat, lng]);
                return;
            }
            marker = L.marker([lat, lng], {
                draggable: !SO_VIEW
            }).addTo(map);
            marker.on('dragend', function() {
                const p = marker.getLatLng();
                setLocation(p.lat, p.lng, 'manual');
            });
        }

        // source: 'manual' | 'exact' | 'approx'
        /*
        | Nearest franchise autofill.
        | Picks the closest active franchise (Haversine distance) to the order location and fills
        | Order Type (if empty), State, District, Panchayath and Nearest Franchise from it - only
        | when it is within NEAREST_FRANCHISE_MAX_KM. Every field stays editable: a franchise chosen
        | by hand is never overwritten, and neither is an Order Type / location the user set by hand.
        | Values are set without firing the State/District/Panchayath change handlers, because those
        | reset the franchise list.
        */
        const NEAREST_FRANCHISE_MAX_KM = CFG.nearestMaxKm;
        const FRANCHISES = CFG.franchises;
        const FRANCHISE_READONLY = CFG.isView; // View Details
        const $franchise = $('#franchise'),
            $fHint = $('#soFranchiseHint');
        let franchiseAutoSet = false; // current franchise value was set by this feature
        let franchiseManual = false; // user picked a franchise by hand
        let locationManual = false; // user picked state/district/panchayath by hand
        let orderTypeAutoSet = false; // Order Type was set by this feature
        let autoRun = 0; // ignores stale async results when the pin moves quickly

        function haversineKm(lat1, lon1, lat2, lon2) {
            const R = 6371,
                rad = Math.PI / 180;
            const dLat = (lat2 - lat1) * rad,
                dLon = (lon2 - lon1) * rad;
            const a = Math.sin(dLat / 2) ** 2 +
                Math.cos(lat1 * rad) * Math.cos(lat2 * rad) * Math.sin(dLon / 2) ** 2;
            return 2 * R * Math.asin(Math.sqrt(a));
        }

        function nearestFranchise(lat, lng) {
            let best = null;
            FRANCHISES.forEach(function(f) {
                if (f.lat === null || f.lng === null || !isFinite(f.lat) || !isFinite(f.lng)) {
                    return;
                }
                const d = haversineKm(lat, lng, f.lat, f.lng);
                if (!best || d < best.km) {
                    best = {
                        f: f,
                        km: d
                    };
                }
            });
            return best;
        }

        function fillOptions($sel, items, idKey, textKey, placeholder) {
            $sel.empty().append('<option value="">' + placeholder + '</option>');
            (items || []).forEach(function(x) {
                $sel.append($('<option>').val(x[idKey]).text(x[textKey]));
            });
        }

        // State / District / Panchayath are no longer on the form (the server takes them
        // from the selected franchise), so there is nothing to fill here.
        function fillFranchiseLocation(f, run) {}


        /*
        | Ranked suggestions: the closest franchises with their distance, one click to use.
        | The closest is still auto-selected below; this just lets the user see and pick others.
        */
        const $rank = $('#soFranchiseRank');

        function rankFranchises(lat, lng, limit) {
            const out = [];
            FRANCHISES.forEach(function(f) {
                if (f.lat === null || f.lng === null || !isFinite(f.lat) || !isFinite(f.lng)) {
                    return;
                }
                out.push({
                    f: f,
                    km: haversineKm(lat, lng, f.lat, f.lng)
                });
            });
            out.sort(function(a, b) {
                return a.km - b.km;
            });
            return out.slice(0, limit);
        }

        function renderRank(lat, lng) {
            if (FRANCHISE_READONLY) {
                return;
            }
            const list = rankFranchises(lat, lng, 3);
            if (!list.length) {
                $rank.hide().empty();
                return;
            }
            const selected = String($franchise.val() || '');
            $rank.empty().show();
            $rank.append('<div class="small fw-semibold mb-1">Nearest franchises</div>');
            list.forEach(function(x, i) {
                const far = x.km > NEAREST_FRANCHISE_MAX_KM;
                const isSel = selected === String(x.f.id);
                const $row = $(
                        '<div class="d-flex justify-content-between align-items-center border rounded px-2 py-1 mb-1"></div>'
                    )
                    .css(isSel ? {
                        borderColor: '#2f7d4f',
                        background: 'rgba(47,125,79,.08)'
                    } : {});
                const $label = $('<div class="small"></div>')
                    .append($('<span class="fw-semibold"></span>').text(x.f.name))
                    .append($('<span class="text-muted ms-2"></span>').text(x.km.toFixed(1) + ' km'));
                if (i === 0) {
                    $label.append(' <span class="badge bg-success ms-1">Closest</span>');
                }
                if (far) {
                    $label.append(' <span class="badge bg-warning text-dark ms-1">Far</span>');
                }
                const $btn = $('<button type="button" class="btn btn-sm btn-outline-success"></button>')
                    .text(isSel ? 'Selected' : 'Use').prop('disabled', isSel)
                    .on('click', function() {
                        chooseFranchise(x.f, x.km);
                    });
                $row.append($label, $btn);
                $rank.append($row);
            });
        }

        function chooseFranchise(f, km) {
            if (FRANCHISE_READONLY) {
                return;
            }
            if (!$franchise.find('option[value="' + f.id + '"]').length) {
                $franchise.append($('<option>').val(f.id).text(f.name));
            }
            $franchise.val(String(f.id)).trigger('change'); // counts as a manual choice
            if (!$('input[name="order_type"]:checked').length && $('#franchise_type').length) {
                $('#franchise_type').prop('checked', true).trigger('change');
            }
            fillFranchiseLocation(f, ++autoRun);
            $fHint.removeClass('text-muted text-warning').addClass('text-success')
                .text('Selected ' + f.name + ' (' + km.toFixed(1) + ' km away).');
            renderRank(parseFloat($lat.val()), parseFloat($lng.val()));
        }

        function autoFillFranchise(lat, lng) {
            if (FRANCHISE_READONLY) {
                return;
            }
            if (franchiseManual) {
                renderRank(lat, lng);
                return;
            } // respect manual choice
            const run = ++autoRun;
            const best = nearestFranchise(lat, lng);
            if (best && best.km <= NEAREST_FRANCHISE_MAX_KM) {
                const f = best.f;
                if (!$franchise.find('option[value="' + f.id + '"]').length) {
                    $franchise.append($('<option>').val(f.id).text(f.name));
                }
                $franchise.val(String(f.id)).trigger('change.auto');
                franchiseAutoSet = true;

                // Order Type: only when nothing is chosen yet
                if (!$('input[name="order_type"]:checked').length && $('#franchise_type').length) {
                    $('#franchise_type').prop('checked', true).trigger('change');
                    orderTypeAutoSet = true;
                }

                fillFranchiseLocation(f, run);
                $fHint.removeClass('text-muted text-warning').addClass('text-success')
                    .text('Auto-selected nearest franchise (' + best.km.toFixed(1) +
                        ' km away). You can change it manually.');
                renderRank(lat, lng);
            } else {
                if (franchiseAutoSet) {
                    $franchise.val('');
                }
                franchiseAutoSet = false;
                $fHint.removeClass('text-muted text-success').addClass('text-warning')
                    .text('No franchise found within ' + NEAREST_FRANCHISE_MAX_KM +
                        ' km. Please select one manually.');
                renderRank(lat, lng);
            }
        }

        // Any hand-made selection locks out autofill for that field
        $franchise.on('change', function(e) {
            if (e.namespace === 'auto') {
                return;
            }
            franchiseAutoSet = false;
            franchiseManual = $franchise.val() !== '';
            if (franchiseManual) {
                $fHint.text('');
            }
        });
        $('input[name="order_type"]').on('change', function(e) {
            if (!e.isTrigger) {
                orderTypeAutoSet = false;
            }
        });

        // Values already saved on the order (edit page / validation error) count as chosen
        if ($franchise.val()) {
            franchiseManual = true;
        }

        // Edit page / validation error: show the ranking for the saved location
        (function() {
            const lat0 = parseFloat($lat.val()),
                lng0 = parseFloat($lng.val());
            if (validCoords(lat0, lng0)) {
                renderRank(lat0, lng0);
            }
        })();

        function setLocation(lat, lng, source, note) {
            lat = parseFloat(lat);
            lng = parseFloat(lng);
            $lat.val(lat.toFixed(7));
            $lng.val(lng.toFixed(7));
            ensureMap(lat, lng, source === 'approx' ? 14 : 16);
            placeMarker(lat, lng);
            autoFillFranchise(lat, lng);
            if (source === 'manual') {
                say('success', '&#10003; Location selected on the map.');
            } else if (source === 'approx') {
                say('warning', '&#9888; Approximate location (' + note +
                    '). Please drag the pin to the exact spot.');
            } else {
                say('success', '&#10003; Location found. Please verify the pin.');
            }
        }

        function geocode(query) {
            return $.ajax({
                url: 'https://nominatim.openstreetmap.org/search',
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query,
                    format: 'json',
                    limit: 1,
                    countrycodes: 'in'
                }
            });
        }

        function selectedText(sel) {
            const $o = $(sel + ' option:selected');
            const t = $o.length && $o.val() ? $o.text().trim() : '';
            return t;
        }

        $btn.on('click', function() {
            const address = $('#c_address').val().trim();
            const postOffice = $('#c_post_office').val().trim();
            const thaluk = $('#c_thaluk').val().trim();
            const pincode = $('#c_pincode').val().trim();
            const state = selectedText('#n_state_id');
            const district = selectedText('#n_district_id');

            if (!address) {
                say('danger', 'Please enter the address first.');
                $('#c_address').focus();
                return;
            }
            if (!state) {
                say('danger', 'Please select a state.');
                $('#n_state_id').focus();
                return;
            }
            if (!district) {
                say('danger', 'Please select a district.');
                $('#n_district_id').focus();
                return;
            }

            const join = (...parts) => parts.filter(Boolean).join(', ');
            const pin = /^\d{6}$/.test(pincode) ? pincode : '';

            // most specific -> least specific; label is shown when a fallback is used
            const attempts = [{
                    q: join(address, postOffice, thaluk, district, state, pin, 'India'),
                    label: null
                },
                {
                    q: join(address, thaluk, district, state, 'India'),
                    label: null
                },
                {
                    q: join(postOffice, thaluk, district, state, pin, 'India'),
                    label: 'matched by post office / taluk'
                },
                pin ? {
                    q: join(pin, 'India'),
                    label: 'matched by pincode ' + pin
                } : null,
                {
                    q: join(thaluk, district, state, 'India'),
                    label: 'matched by taluk'
                },
                {
                    q: join(district, state, 'India'),
                    label: 'matched by district only'
                }
            ].filter(Boolean).filter(function(a, i, arr) {
                return arr.findIndex(b => b.q === a.q) === i;
            });

            $btn.prop('disabled', true).html('<i class="ti ti-loader-2"></i> Searching...');
            say('muted', 'Finding location...');

            function finish() {
                $btn.prop('disabled', false).html(BTN_HTML);
            }

            function tryAt(i) {
                if (i >= attempts.length) {
                    finish();
                    say('danger',
                        'Location not found. Please check the address, or click "Select on Map" to pin it manually.'
                    );
                    return;
                }
                geocode(attempts[i].q).done(function(res) {
                    if (res && res.length) {
                        finish();
                        setLocation(res[0].lat, res[0].lon, attempts[i].label ? 'approx' :
                            'exact', attempts[i].label);
                    } else {
                        // Nominatim allows ~1 request/second
                        setTimeout(function() {
                            tryAt(i + 1);
                        }, 1100);
                    }
                }).fail(function() {
                    finish();
                    say('danger',
                        'Could not reach the location service. Check your connection, or click "Select on Map" to pin it manually.'
                    );
                });
            }
            tryAt(0);
        });

        $('#soToggleMapBtn').on('click', function() {
            if ($mapBox.is(':visible') && map) {
                $mapBox.hide();
                return;
            }
            const lat = parseFloat($lat.val()),
                lng = parseFloat($lng.val());
            if (validCoords(lat, lng)) {
                ensureMap(lat, lng, 16);
                placeMarker(lat, lng);
            } else {
                ensureMap(10.8505, 76.2711, 8); // Kerala, same default as the franchise form
                say('muted', 'Click on the map to drop a pin.');
            }
        });

        // Typed / pasted coordinates move the pin
        $lat.add($lng).on('change', function() {
            const lat = parseFloat($lat.val()),
                lng = parseFloat($lng.val());
            if ($lat.val().trim() === '' && $lng.val().trim() === '') {
                return;
            }
            if (!validCoords(lat, lng)) {
                say('danger', 'Enter a valid latitude (-90 to 90) and longitude (-180 to 180).');
                return;
            }
            setLocation(lat, lng, 'manual');
        });

        // Address edited after a pin was set -> remind, don't silently keep a stale pin
        $('#c_address, #c_post_office, #c_thaluk, #c_pincode, #n_state_id, #n_district_id').on('change',
            function() {
                if ($lat.val().trim() !== '') {
                    say('warning',
                        'Address changed. Click "Get Location from Address" to refresh the location.');
                }
            });

        // Edit page / validation error: show the saved pin
        const l0 = parseFloat($lat.val()),
            g0 = parseFloat($lng.val());
        if (validCoords(l0, g0)) {
            ensureMap(l0, g0, 16);
            placeMarker(l0, g0);
            say('muted',
                'Saved location shown. Change the address and click "Get Location from Address" to update it.'
            );
        }
    });