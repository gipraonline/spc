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

                            // Set HSN
                            row.find('.c_hsn_code').val(data.c_hsn_code);
                            // Set gst percentage
                            row.find('.gst_percentage').val(data.n_gst_percentage);
                            row.find('.n_product_id').val(data.n_product_id);

                            // MRP is stored on the row itself (full precision)
                            calculateRowWithMrp(row, data.n_mrp, data.n_gst_percentage);

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

            $(document).on('change', '.packSize-select', function() {

                let row = $(this).closest('.new-product-row');
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

                        // Set HSN
                        row.find('.c_hsn_code').val(data.c_hsn_code);
                        // Set gst percentage
                        row.find('.gst_percentage').val(data.n_gst_percentage);
                        row.find('.n_product_id').val(data.n_product_id);

                        // MRP is stored on the row itself (full precision)
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
            | Quantity / Discount Change Event (new and saved rows)
            |--------------------------------------------------------------------------
            */

            $(document).on('input change', '.qty, .discount', function() {
                renderRow($(this).closest('tr'));
            });
        });


        /*
        |--------------------------------------------------------------------------
        | Calculation (MRP includes GST)
        | One place for all the maths. NOTHING is rounded here; values are rounded
        | only when they are written to the screen (toFixed below).
        |--------------------------------------------------------------------------
        */
        function rowAmounts(row) {
            const qty = Math.max(parseFloat(row.find('.qty').val()) || 0, 0);
            let discount = Math.max(parseFloat(row.find('.discount').val()) || 0, 0);
            const gstPct = parseFloat(row.find('.gst_percentage').val()) || 0;

            // Full-precision MRP: stored on the row for new lines; for saved lines
            // it is rebuilt from the saved ex-GST price (133.33 x 1.05 = 140.00).
            let mrp = row.data('mrp');
            if (mrp === undefined) {
                const shown = parseFloat(row.find('.price').val()) || 0;
                mrp = Math.round(shown * (1 + gstPct / 100) * 100) / 100;
            }

            // Price is the same 2-decimal value shown in the Price field (133.33),
            // so the line and the Order Summary always use the same numbers.
            const r2 = function(x) { return Math.round((x + 1e-9) * 100) / 100; };
            const price = r2(mrp / (1 + gstPct / 100));   // 133.33
            const gross = r2(price * qty);                // 1333.30
            discount = Math.min(discount, gross);         // discount cannot exceed line value
            const taxable = r2(gross - discount);         // 1233.30
            const gst = r2(taxable * gstPct / 100);       // 61.67
            return {
                price: price,
                gross: gross,
                discount: discount,
                taxable: taxable,
                gst: gst,
                total: r2(taxable + gst)                  // 1294.97
            };
        }

        // Display only (2 decimals). All calculations above use full precision.
        function fmt(n) {
            return (Number(n) || 0).toFixed(2);
        }

        function renderRow(row) {
            const a = rowAmounts(row);
            if (row.data('mrp') !== undefined) {
                row.find('.price').val(fmt(a.price));
            }
            row.find('.gst_amount').val(fmt(a.gst));
            row.find('.discounted_price').val(fmt(a.taxable));
            row.find('.total').val(fmt(a.total));
            calculateSummary();
        }

        function calculateRowWithMrp(row, mrp, gstPercentage) {
            row.data('mrp', parseFloat(mrp) || 0);
            row.find('.gst_percentage').val(parseFloat(gstPercentage) || 0);
            renderRow(row);
        }

        function clearRowPricing(row) {
            row.removeData('mrp');
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
        | Summary Totals (sum of full-precision line values, rounded once)
        |--------------------------------------------------------------------------
        */
        function calculateSummary() {
            let sales = 0,
                disc = 0,
                gst = 0,
                net = 0;

            $('#productTable tbody tr').each(function() {
                const a = rowAmounts($(this));
                sales += a.gross;
                disc += a.discount;
                gst += a.gst;
                // Net Sales = exactly the sum of the Total (MRP) shown on each line
                net += parseFloat(fmt(a.total));
            });

            $('#summaryTotalSales').val(fmt(sales));
            $('#summaryTotalDiscount').val(fmt(disc));
            $('#summaryGstAmount').val(fmt(gst));
            $('#summaryNetSales').val(fmt(net));
            $('#n_amount_to_pay').val(fmt(net));
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
            }else {
        lookupCard.classList.add('d-none');
        $("#c_customer_code").val(CFG.customerCode);
        $("#n_customer_id").val('');
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
                            $('[name="c_post_office"]').val(data.customer.c_post_office || '');
                            $('[name="c_thaluk"]').val(data.customer.c_thaluk || '');

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
                            // "change" lets the nearest-franchise search re-run for the loaded customer
                            $('#n_district_id').val(selectedDistrict).trigger('change');
                        }
                    }
                }
            });
        }
    });

;

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
            }
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

    /*
    |--------------------------------------------------------------------------
    | Nearest franchise from the customer address
    |--------------------------------------------------------------------------
    | Runs when the "Franchise" Order Type radio is clicked (and again when the
    | customer address changes while Franchise is selected):
    |   1. customer address -> latitude / longitude (OpenStreetMap Nominatim),
    |      stored in the hidden #so_latitude / #so_longitude fields
    |   2. closest active franchise within spc.nearest_franchise_max_km is selected
    |   3. if the address cannot be located, fall back to a District / State
    |      match on the server
    | A franchise picked by hand (or already saved on the order) is never overwritten.
    */
    $(function() {
        if (CFG.isView) { return; }

        const $lat = $('#so_latitude'), $lng = $('#so_longitude');
        const $franchise = $('#franchise'), $fHint = $('#soFranchiseHint'), $rank = $('#soFranchiseRank');
        const FRANCHISES = CFG.franchises, MAX_KM = CFG.nearestMaxKm;

        let franchiseManual = $franchise.val() !== ''; // saved or hand-picked franchise is never overwritten
        let franchiseAutoSet = false;
        let runId = 0, timer = null;

        function say(type, text) {
            $fHint.removeClass('text-muted text-success text-warning text-danger')
                .addClass('text-' + type).text(text);
        }

        function isFranchiseOrder() {
            return $('input[name="order_type"]:checked').val() === 'franchise';
        }

        function sel(id) {
            const $o = $(id + ' option:selected');
            return $o.length && $o.val() ? $o.text().trim() : '';
        }

        /* ---------- distance helpers ---------- */
        function haversineKm(a, b, c, d) {
            const R = 6371, r = Math.PI / 180, dLat = (c - a) * r, dLon = (d - b) * r;
            const x = Math.sin(dLat / 2) ** 2 + Math.cos(a * r) * Math.cos(c * r) * Math.sin(dLon / 2) ** 2;
            return 2 * R * Math.asin(Math.sqrt(x));
        }

        function rankFranchises(lat, lng, limit) {
            return FRANCHISES
                .filter(f => f.lat !== null && f.lng !== null && isFinite(f.lat) && isFinite(f.lng))
                .map(f => ({ f: f, km: haversineKm(lat, lng, f.lat, f.lng) }))
                .sort((a, b) => a.km - b.km)
                .slice(0, limit);
        }

        function setFranchise(f) {
            if (!$franchise.find('option[value="' + f.id + '"]').length) {
                $franchise.append($('<option>').val(f.id).text(f.name));
            }
            $franchise.val(String(f.id)); // .val() does not fire "change", so it stays an "auto" choice
        }

        function renderRank(lat, lng) {
            const list = rankFranchises(lat, lng, 3);
            if (!list.length) { $rank.hide().empty(); return; }
            const selected = String($franchise.val() || '');
            $rank.empty().show().append('<div class="small fw-semibold mb-1">Nearest franchises</div>');
            list.forEach(function(x, i) {
                const isSel = selected === String(x.f.id);
                const $row = $('<div class="d-flex justify-content-between align-items-center border rounded px-2 py-1 mb-1"></div>')
                    .css(isSel ? { borderColor: '#2f7d4f', background: 'rgba(47,125,79,.08)' } : {});
                const $label = $('<div class="small"></div>')
                    .append($('<span class="fw-semibold"></span>').text(x.f.name))
                    .append($('<span class="text-muted ms-2"></span>').text(x.km.toFixed(1) + ' km'));
                if (i === 0) { $label.append(' <span class="badge bg-success ms-1">Closest</span>'); }
                if (x.km > MAX_KM) { $label.append(' <span class="badge bg-warning text-dark ms-1">Far</span>'); }
                const $btn = $('<button type="button" class="btn btn-sm btn-outline-success"></button>')
                    .text(isSel ? 'Selected' : 'Use').prop('disabled', isSel)
                    .on('click', function() {
                        franchiseManual = true;
                        franchiseAutoSet = false;
                        setFranchise(x.f);
                        say('success', 'Selected ' + x.f.name + ' (' + x.km.toFixed(1) + ' km away).');
                        renderRank(lat, lng);
                    });
                $row.append($label, $btn);
                $rank.append($row);
            });
        }

        /* ---------- address -> lat/lng (OpenStreetMap Nominatim) ---------- */
        function geocodeAddress() {
            const dfd = $.Deferred();
            const address = $('#c_address').val().trim(), postOffice = $('#c_post_office').val().trim();
            const thaluk = $('#c_thaluk').val().trim(), pincode = $('#c_pincode').val().trim();
            const state = sel('#n_state_id'), district = sel('#n_district_id');

            if (!address || !state || !district) { return dfd.reject('missing').promise(); }

            const join = (...p) => p.filter(Boolean).join(', ');
            const pin = /^\d{6}$/.test(pincode) ? pincode : '';
            // most specific -> least specific
            const attempts = [
                join(address, postOffice, thaluk, district, state, pin, 'India'),
                join(address, thaluk, district, state, 'India'),
                join(postOffice, thaluk, district, state, pin, 'India'),
                pin ? join(pin, 'India') : null,
                join(thaluk, district, state, 'India'),
                join(district, state, 'India')
            ].filter(Boolean).filter((q, i, arr) => arr.indexOf(q) === i);

            (function tryAt(i) {
                if (i >= attempts.length) { dfd.reject('notfound'); return; }
                $.ajax({
                    url: 'https://nominatim.openstreetmap.org/search',
                    type: 'GET',
                    dataType: 'json',
                    data: { q: attempts[i], format: 'json', limit: 1, countrycodes: 'in' }
                }).done(function(res) {
                    if (res && res.length) {
                        dfd.resolve({ lat: parseFloat(res[0].lat), lng: parseFloat(res[0].lon) });
                    } else {
                        // Nominatim allows ~1 request/second
                        setTimeout(function() { tryAt(i + 1); }, 1100);
                    }
                }).fail(function() { dfd.reject('network'); });
            })(0);

            return dfd.promise();
        }

        /* ---------- fallback: match on district / state via the server ---------- */
        function fallbackByDistrict(myRun) {
            const stateId = $('#n_state_id').val(), districtId = $('#n_district_id').val();
            if (franchiseManual || (!stateId && !districtId)) {
                say('warning', 'Could not locate the address. Please select the franchise manually.');
                return;
            }
            fetch(CFG.urlNearest, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CFG.csrf
                },
                body: JSON.stringify({ state_id: stateId, district_id: districtId })
            }).then(r => r.json()).then(function(data) {
                if (myRun !== runId || franchiseManual) { return; }
                if (data.success && data.franchises && data.franchises.length) {
                    const f = data.franchises[0];
                    setFranchise({
                        id: f.n_store_id,
                        name: f.c_store_name + (f.c_store_code ? ' (' + f.c_store_code + ')' : '')
                    });
                    franchiseAutoSet = true;
                    say('warning', 'Exact location not found; franchise selected by ' + data.matched_on + '. You can change it.');
                } else {
                    say('warning', 'No franchise found for this area. Please select one manually.');
                }
            }).catch(function() {
                say('danger', 'Unable to find a franchise. Please select one manually.');
            });
        }

        /* ---------- main: runs when "Franchise" is clicked (and when the address changes later) ---------- */
        function processFranchise() {
            if (!isFranchiseOrder()) { return; }
            const myRun = ++runId;
            say('muted', 'Finding nearest franchise from the customer address...');

            geocodeAddress().done(function(pt) {
                if (myRun !== runId) { return; }
                $lat.val(pt.lat.toFixed(7));
                $lng.val(pt.lng.toFixed(7));

                const best = rankFranchises(pt.lat, pt.lng, 1)[0];
                if (!franchiseManual) {
                    if (best && best.km <= MAX_KM) {
                        setFranchise(best.f);
                        franchiseAutoSet = true;
                        say('success', 'Nearest franchise selected: ' + best.f.name + ' (' + best.km.toFixed(1) + ' km away). You can change it.');
                    } else {
                        if (franchiseAutoSet) { $franchise.val(''); }
                        franchiseAutoSet = false;
                        say('warning', 'No franchise within ' + MAX_KM + ' km. Please select one manually.');
                    }
                } else {
                    say('muted', '');
                }
                renderRank(pt.lat, pt.lng);
            }).fail(function(reason) {
                if (myRun !== runId) { return; }
                $lat.val('');
                $lng.val('');
                $rank.hide().empty();
                if (reason === 'missing') {
                    say('warning', 'Enter the customer address, state and district. The nearest franchise is picked automatically once they are filled.');
                } else {
                    fallbackByDistrict(myRun);
                }
            });
        }

        // A hand-picked franchise is never overwritten
        $franchise.on('change', function() {
            franchiseAutoSet = false;
            franchiseManual = $franchise.val() !== '';
            if (franchiseManual) { say('muted', ''); }
        });

        // Clicking "Franchise" starts the search; clicking "Company" cancels any pending one
        $(document).on('click', '#franchise_type', processFranchise);
        $(document).on('click', '#company', function() { runId++; });

        // Address changed while Franchise is selected -> refresh (debounced)
        $('#c_address, #c_post_office, #c_thaluk, #c_pincode, #n_state_id, #n_district_id').on('change', function() {
            if (!isFranchiseOrder()) { return; }
            clearTimeout(timer);
            timer = setTimeout(processFranchise, 800);
        });
    });