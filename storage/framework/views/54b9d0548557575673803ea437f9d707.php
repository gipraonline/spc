<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
:root {
    --primary-green: #1b3e86;
    --accent-orange: #1b3e86;
    --deep-slate: #1e293b;
    --glass-bg: #fdfdfe;
    --border-radius-lg: 18px;
    --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.04), 0 5px 15px rgba(0, 0, 0, 0.02);
}

/* Architectural Layout */
.premium-edit-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    position: relative;
}

/* Signature Accent Line */
.premium-edit-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--accent-orange) 0%, #ffae42 100%);
    z-index: 10;
}

.card-header-premium {
    padding: 2.2rem 2.5rem 1.2rem;
    background: #fff;
}

.page-main-title {
    font-weight: 800;
    font-size: 1.4rem;
    color: var(--deep-slate);
    letter-spacing: -0.8px;
}

/* Sectional Typography */
.field-group-title {
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--accent-orange);
    letter-spacing: 1.5px;
    margin-bottom: 1.4rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.field-group-title::after {
    content: '';
    height: 1px;
    flex: 1;
    background: linear-gradient(90deg, #f1f5f9 0%, transparent 100%);
}

/* Modern Inputs */
.form-label {
    font-weight: 700;
    color: #475569;
    font-size: 0.85rem;
    margin-bottom: 0.6rem;
}

.form-control,
.form-select {
    border-radius: 12px;
    padding: 0.85rem 1.1rem;

    background-color: #f8fafc;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    color: var(--deep-slate);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--accent-orange);
    background-color: #ffffff;
    box-shadow: 0 8px 20px rgba(247, 148, 30, 0.08);
    transform: translateY(-1px);
}

/* Action Buttons */
.btn-update-action {
    background: var(--accent-orange);
    border: none;
    padding: 14px 40px;
    border-radius: 12px;
    font-weight: 800;
    color: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(247, 148, 30, 0.15);
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-update-action:hover {
    background: #1b3e86;
    box-shadow: 0 12px 25px rgba(247, 148, 30, 0.25);
    transform: translateY(-2px);
}

.btn-cancel-action {
    border-radius: 12px;
    padding: 14px 30px;
    font-weight: 700;
    border: 2px solid #f1f5f9;
    color: #64748b;
    transition: all 0.2s ease;
}

#franchiseMap {
    width: 100%;
    height: 450px;
    border-radius: 12px;
    margin-bottom: 25px;
    overflow: hidden;
}

.location-status {
    font-weight: 600;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="card premium-edit-card mb-4">
    <div class="card-header-premium">
        <h5 class="page-main-title mb-0">Edit Store</h5>
    </div>

    <div class="card-body p-4 p-md-5 pt-md-4">
        <form id="frm_create" method="POST" action="<?php echo e(route('admin.franchises.update', $franchise)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <!-- Section 1: Store Configuration -->
            <div class="field-group-title">
                <i class="ti ti-settings-automation fs-5"></i> Store Configuration
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <label for="c_store_code" class="form-label">Store Code *</label>
                    <input type="text" id="c_store_code" name="c_store_code" data-message="Enter valid Store Code"
                        max-length="20" value="<?php echo e(old('c_store_code', $franchise->c_store_code)); ?>" required
                        class="form-control mandatory">
                    <div class="text-danger mt-1 fs-2"></div>
                    <?php $__errorArgs = ['c_store_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-7">
                    <label for="c_store_name" class="form-label">Store Name *</label>
                    <input type="text" id="c_store_name" name="c_store_name" data-message="Please enter Name"
                        maxlength="100" value="<?php echo e(old('c_store_name', $franchise->c_store_name)); ?>" required
                        class="form-control mandatory">
                    <?php $__errorArgs = ['c_store_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                    <label for="c_owner_name" class="form-label">Owner Name *</label>

                    <input type="text" id="c_owner_name" name="c_owner_name"
                        value="<?php echo e(old('c_owner_name', $franchise->c_owner_name)); ?>" maxlength="100"
                        class="form-control mandatory" placeholder="Franchise owner name">

                    <?php $__errorArgs = ['c_owner_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-12">
                    <label for="c_store_address" class="form-label">Address</label>
                    <input type="text" id="c_store_address" name="c_store_address" maxlength="255"
                        value="<?php echo e(old('c_store_address', $franchise->c_store_address)); ?>" class="form-control">
                    <?php $__errorArgs = ['c_store_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="row g-4 mb-4">



                    <div class="col-md-4">
                        <label for="n_state_id" class="form-label">State *</label>

                        <select id="n_state_id" name="n_state_id" class="form-select" required>
                            <option value="">Select State</option>

                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($state->n_state_id); ?>"
                                <?php echo e(old('n_state_id', $franchise->n_state_id) == $state->n_state_id ? 'selected' : ''); ?>>
                                <?php echo e($state->name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <?php $__errorArgs = ['n_state_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>


                    <div class="col-md-4">
                        <label for="n_district_id" class="form-label">District *</label>

                        <select id="n_district_id" name="n_district_id" class="form-select" required>
                            <option value="">Select District</option>

                            <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($district->id); ?>"
                                <?php echo e(old('n_district_id', $franchise->n_district_id) == $district->id ? 'selected' : ''); ?>>
                                <?php echo e($district->district_name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <?php $__errorArgs = ['n_district_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>


                    
                    <div class="col-md-4">
                        <label for="c_panchayath" class="form-label">Panchayath *</label>

                        <input type="text" id="c_panchayath" name="c_panchayath" class="form-control" maxlength="150"
                            required placeholder="Enter Panchayath"
                            value="<?php echo e(old('c_panchayath', $franchise->panchayath?->panchayath_name)); ?>">

                        <?php $__errorArgs = ['c_panchayath'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>


                    
                    <div class="field-group-title mt-5">
                        <i class="ti ti-map-pin fs-5"></i>
                        Franchise GPS Location
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6"> <label for="latitude" class="form-label"> Latitude * </label> <input
                                type="text" id="latitude" name="latitude"
                                value="<?php echo e(old('latitude', $franchise->latitude)); ?>" class="form-control"
                                placeholder="Latitude" readonly required> <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div
                                class="text-danger mt-1 fs-2"> <?php echo e($message); ?> </div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> </div>
                        <div class="col-md-6"> <label for="longitude" class="form-label"> Longitude * </label> <input
                                type="text" id="longitude" name="longitude"
                                value="<?php echo e(old('longitude', $franchise->longitude)); ?>" class="form-control"
                                placeholder="Longitude" readonly required> <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div
                                class="text-danger mt-1 fs-2"> <?php echo e($message); ?> </div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> </div>
                    </div>
                    <div class="mb-4 d-flex align-items-center gap-2 flex-wrap"> <button type="button"
                            id="getLocationBtn" class="btn btn-outline-success"> <i class="ti ti-map-pin-search"></i>
                            Get Location from Address </button> <button type="button" id="selectLocationBtn"
                            class="btn btn-outline-primary"> <i class="ti ti-map-pin"></i> Select Location on Map
                        </button> <span id="locationStatus" class="ms-2 text-muted location-status">
                            <?php if($franchise->latitude && $franchise->longitude): ?> ✓ Existing franchise location loaded
                            <?php else: ?> Select or detect the franchise location <?php endif; ?> </span> </div>
                    <div id="franchiseMap"
                        style="<?php echo e($franchise->latitude && $franchise->longitude ? '' : 'display:none;'); ?>"> </div>
                </div>

                <!-- Section 2: Contact & Operational Details -->
                <div class="field-group-title mt-5">
                    <i class="ti ti-address-book fs-5"></i> Contact & Status
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label for="c_store_email" class="form-label">Email</label>
                        <input type="email" id="c_store_email" name="c_store_email"
                            value="<?php echo e(old('c_store_email', $franchise->c_store_email)); ?>" class="form-control">
                        <?php $__errorArgs = ['c_store_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="n_store_phone" class="form-label">Phone</label>
                        <input type="text" id="n_store_phone" name="n_store_phone" max-length="10"
                            value="<?php echo e(old('n_store_phone', $franchise->n_store_phone)); ?>" class="form-control">
                        <?php $__errorArgs = ['n_store_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-12">
                        <label for="c_store_status" class="form-label">Status *</label>
                        <select id="c_store_status" name="c_store_status" data-message="Please select Status" required
                            class="form-select mandatory">
                            <option value="Y"
                                <?php echo e(old('c_store_status', $franchise->c_store_status) === 'Y' ? 'selected' : ''); ?>>
                                Active</option>
                            <option value="N"
                                <?php echo e(old('c_store_status', $franchise->c_store_status) === 'N' ? 'selected' : ''); ?>>
                                Inactive</option>
                        </select>
                        <?php $__errorArgs = ['c_store_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger mt-1 fs-2"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Footer Action Bar -->
                <div class="pt-4 border-top d-flex gap-3">
                    <button type="submit" id="btn_create" class="btn buttonSpc">
                        <i class="ti ti-refresh fs-4"></i> Update Record
                    </button>
                    <a href="<?php echo e(route('admin.franchises.index')); ?>" class="btn btn-cancel-action">Cancel</a>
                </div>
        </form>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?> <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
$(document).ready(function() {
    /* | VARIABLES | */
    let franchiseMap = null;
    let franchiseMarker = null;
    /* | EXISTING FRANCHISE LOCATION |*/
    const existingLatitude = parseFloat($('#latitude').val());
    const existingLongitude = parseFloat($('#longitude').val());
    /*  | STATE → DISTRICT | */
    $('#n_state_id').on('change', function() {
        let stateId = $(this).val();
        $('#n_district_id').html('<option value="">Loading...</option>');
        if (!stateId) {
            $('#n_district_id').html('<option value="">Select District</option>');
            return;
        }
        $.ajax({
            type: 'GET',
            url: "<?php echo e(route('admin.filterDistrict')); ?>",
            data: {
                state: stateId
            },
            dataType: 'json',
            success: function(response) {
                $('#n_district_id').html('<option value="">Select District</option>');
                if (response.districts && response.districts.length > 0) {
                    $.each(response.districts, function(index, district) {
                        $('#n_district_id').append('<option value="' + district.id +
                            '">' + district.district_name + '</option>');
                    });
                } else {
                    $('#n_district_id').html(
                        '<option value="">No Districts Found</option>');
                }
            },
            error: function(xhr) {
                console.error('District loading failed:', xhr.responseText);
                $('#n_district_id').html(
                    '<option value="">Unable to load districts</option>');
            }
        });
    });
    /* | INITIALIZE LEAFLET MAP |*/
    function initializeMap(latitude = 10.8505, longitude = 76.2711, zoom = 8) {
        $('#franchiseMap')
            .show();
        /*  Create map only once */
        if (!franchiseMap) {
            franchiseMap = L.map('franchiseMap').setView([latitude, longitude],
                zoom
            );
            /*  OpenStreetMap  */
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(
                franchiseMap
            );
            /*  Manual map click  */
            franchiseMap.on('click', function(e) {
                setLocation(e.latlng.lat, e.latlng.lng, true);
            });
        } else {
            franchiseMap.setView([latitude, longitude], zoom);
        }
        /*  Fix Leaflet Rendering  */
        setTimeout(function() {
            franchiseMap.invalidateSize();
        }, 300);
    }
    /* SET LOCATION */
    function setLocation(latitude, longitude, manual = false) {
        latitude = parseFloat(latitude).toFixed(7);
        longitude = parseFloat(longitude).toFixed(7);
        /*  Hidden/readonly inputs */
        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
        const latLng = [parseFloat(latitude), parseFloat(
            longitude)];
        /*  Create / Move Marker*/
        if (franchiseMarker) {
            franchiseMarker.setLatLng(latLng);
        } else {
            franchiseMarker = L.marker(latLng, {
                draggable: true
            }).addTo(
                franchiseMap);
            /*  Drag marker */
            franchiseMarker.on('dragend', function(e) {
                const position = e.target.getLatLng();
                setLocation(position.lat, position.lng, true);
            });
        }
        /*  Move map */
        franchiseMap.setView(latLng, 16);
        /*  Status */
        if (manual) {
            $('#locationStatus').removeClass('text-muted text-danger').addClass('text-success').html(
                '✓ Location manually selected');
        } else {
            $('#locationStatus').removeClass('text-muted text-danger').addClass('text-success').html(
                '✓ Location found. Please verify the marker.');
        }
    }
    /* SELECT LOCATION ON MAP */
    $('#selectLocationBtn').on('click', function() {
        /* | If existing coordinates exist, | open map at existing location. */
        if (!isNaN(existingLatitude) && !isNaN(existingLongitude)) {
            initializeMap(existingLatitude, existingLongitude, 16);
            setLocation(existingLatitude, existingLongitude, false);
        } else {
            initializeMap();
        }
    });
    /*  GEOCODING FUNCTION  */
    function searchLocation(query, callback) {
        $.ajax({
            url: 'https://nominatim.openstreetmap.org/search',
            type: 'GET',
            data: {
                q: query,
                format: 'json',
                limit: 5,
                countrycodes: 'in'
            },
            dataType: 'json',
            success: function(response) {
                callback(response);
            },
            error: function(xhr) {
                console.error('Geocoding error:', xhr.responseText);
                callback([]);
            }
        });
    }
    /*  GET LOCATION FROM ADDRESS  */
    $('#getLocationBtn').on('click', function() {
        const address = $('#c_store_address').val().trim();
        const state = $('#n_state_id option:selected').text().trim();
        const district = $('#n_district_id option:selected').text().trim();
        const panchayath = $('#c_panchayath').val()
            .trim();
        /*  VALIDATION  */
        if (!address) {
            $('#locationStatus').removeClass('text-muted text-success').addClass('text-danger').text(
                'Please enter the address first.');
            $('#c_store_address').focus();
            return;
        }
        if (!state || state === 'Select State') {
            $('#locationStatus').removeClass('text-muted text-success').addClass('text-danger').text(
                'Please select a state.');
            $('#n_state_id').focus();
            return;
        }
        if (!district || district === 'Select District') {
            $('#locationStatus').removeClass('text-muted text-success').addClass('text-danger').text(
                'Please select a district.');
            $('#n_district_id').focus();
            return;
        }
        if (!panchayath) {
            $('#locationStatus').removeClass('text-muted text-success').addClass('text-danger').text(
                'Please enter the Panchayath.');
            $('#c_panchayath').focus();
            return;
        }
        /*  Button Loading  */
        const button = $('#getLocationBtn');
        button.prop('disabled', true);
        button.html('<i class="ti ti-loader-2"></i> Searching...');
        $('#locationStatus').removeClass('text-success text-danger').addClass('text-muted').text(
            'Finding location...'
        );
        /*  Search Queries  */
        const searches = [ /* | 1. Full Address */ address + ', ' + panchayath + ', ' + district +
            ', ' + state + ', India', /* | 2. Address + District + State */ address + ', ' +
            district + ', ' + state + ', India', /* | 3. Panchayath + District + State */
            panchayath + ', ' + district + ', ' + state + ', India', /* | 4. District + State */
            district + ', ' + state + ', India'
        ];
        /* TRY SEARCH  */
        function trySearch(index) {
            if (index >= searches.length) {
                button.prop('disabled', false);
                button.html('<i class="ti ti-map-pin-search"></i> Get Location from Address');
                $('#locationStatus').removeClass('text-muted text-success').addClass('text-danger')
                    .html('Location not found. Please select the location manually on the map.');
                initializeMap();
                return;
            }
            const query = searches[index];
            searchLocation(query, function(results) {
                if (results && results.length > 0) {
                    const result = results[0];
                    const latitude = parseFloat(result.lat);
                    const longitude = parseFloat(result
                        .lon
                    );
                    /*  Open map */
                    initializeMap(latitude, longitude,
                        16
                    );
                    /* Set marker */
                    setLocation(latitude, longitude, false);
                    $('#locationStatus').removeClass('text-muted text-danger').addClass(
                        'text-success').html(
                        '✓ Location found. Please verify the marker on the map.');
                    button.prop('disabled', false);
                    button.html(
                        '<i class="ti ti-map-pin-search"></i> Get Location from Address');
                    return;
                }
                /*  Try next query*/
                setTimeout(function() {
                    trySearch(index + 1);
                }, 1200);
            });
        }
        trySearch(0);
    });
    /* INITIALIZE EXISTING LOCATION */
    if (!isNaN(existingLatitude) && !isNaN(existingLongitude)) {
        initializeMap(existingLatitude, existingLongitude, 16);
        setLocation(existingLatitude, existingLongitude, false);
        $('#locationStatus').removeClass('text-muted text-danger').addClass('text-success').html(
            '✓ Existing franchise location loaded. You can move the marker.');
    }
    /*  FORM SUBMIT VALIDATION */
    $('#frm_create').on('submit', function(e) {
        const latitude = $('#latitude').val().trim();
        const longitude = $('#longitude').val().trim();
        if (!latitude || !longitude) {
            e.preventDefault();
            $('#locationStatus').removeClass('text-muted text-success').addClass('text-danger').text(
                'Please select the franchise location on the map.');
            initializeMap();
            return false;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SPC\resources\views/admin/stores/edit.blade.php ENDPATH**/ ?>