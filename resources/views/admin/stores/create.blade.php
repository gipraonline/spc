@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<style>
#map {
    width: 100%;
    height: 450px;
    margin-top: 20px;
    border-radius: 15px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.leaflet-control-geocoder {
    width: 300px;
}

.leaflet-control-geocoder-form input {
    width: 250px;
}

/* Premium Design Tokens */
:root {
    --primary-green: #1b3e86;
    --accent-orange: #F7941E;
    --deep-slate: #1e293b;
    --glass-bg: #fdfdfe;
    --input-border: #e2e8f0;
    --border-radius-lg: 18px;
    --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.04), 0 5px 15px rgba(0, 0, 0, 0.02);
}

/* Architectural Layout */
.premium-form-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    position: relative;
}

/* Signature Accent Line */
.premium-form-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--primary-green) 0%, #51cf66 100%);
    z-index: 10;
}

.card-header-premium {
    padding: 2.2rem 2.5rem 1.2rem;
    background: #fff;
    border-bottom: none;
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
    color: var(--primary-green);
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
    font-weight: 500;
    color: var(--deep-slate);
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-green);
    background-color: #ffffff;
    box-shadow: 0 8px 20px rgba(57, 181, 74, 0.08);
    transform: translateY(-1px);
}

/* Validation Messages */
.text-danger.fs-2 {
    font-weight: 600;
    font-size: 0.75rem !important;
    padding-left: 4px;
    letter-spacing: 0.2px;
}

/* Action Buttons */
.btn-create-action {
    background: var(--primary-green);
    border: none;
    padding: 14px 40px;
    border-radius: 12px;
    font-weight: 800;
    color: #fff;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(57, 181, 74, 0.15);
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-create-action:hover {
    background: #1b3e86;
    box-shadow: 0 12px 25px rgba(57, 181, 74, 0.25);
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

.btn-cancel-action:hover {
    background: #f1f5f9;
    color: #475569;
}
</style>

@endpush

@section('content')

<div class="card premium-form-card mb-4">
    <div class="card-header-premium">
        <h5 class="page-main-title mb-0">Add Franchise</h5>
    </div>

    <div class="card-body p-4 p-md-5 pt-md-4">
        <form id="frm_create" method="POST" action="{{ route('admin.franchises.store') }}">
            @csrf

            <!-- Section 1: Record Identity -->
            <div class="field-group-title">
                <i class="ti ti-id-badge-2 fs-5"></i> Identity & Location
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-5">
                    <label for="c_store_code" class="form-label">Franchise Code *</label>
                    <input type="text" id="c_store_code" data-message="Enter valid Store Code" name="c_store_code"
                        value="{{ old('c_store_code') }}" max-length="20" class="form-control mandatory"
                        placeholder="e.g. SPC-001">
                    @error('c_store_code')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-7">
                    <label for="c_store_name" class="form-label">Franchise Name *</label>
                    <input type="text" id="c_store_name" data-message="Please enter Name" name="c_store_name"
                        value="{{ old('c_store_name') }}" maxlength="100" pattern="[A-Za-z0-9\s\-]+"
                        class="form-control mandatory" placeholder="Legal Franchise name">
                    @error('c_store_name')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Owner Name --}}
                <div class="col-md-6">
                    <label for="c_owner_name" class="form-label">Owner Name *</label>
                    <input type="text" id="c_owner_name" name="c_owner_name" value="{{ old('c_owner_name') }}"
                        maxlength="100" class="form-control mandatory" placeholder="Enter Owner Name">

                    @error('c_owner_name')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="c_store_address" class="form-label">Address</label>
                    <input type="text" id="c_store_address" name="c_store_address" value="{{ old('c_store_address') }}"
                        maxlength="255" class="form-control" placeholder="Street, Building, Area...">
                    @error('c_store_address')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- <div class="row g-4 mb-4">

                <div class="col-md-6">
                    <label for="n_state_id" class="form-label">State *</label>
                    <select id="n_state_id" name="n_state_id" class="form-select mandatory">
                        <option value="">Select State</option>
                        @foreach($states as $state)
                        <option value="{{ $state->n_state_id }}"
                            {{ old('n_state_id') == $state->n_state_id ? 'selected' : '' }}>
                            {{ $state->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('n_state_id')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="n_district_id" class="form-label">District *</label>
                    <select id="n_district_id" name="n_district_id" class="form-select mandatory">
                        <option value="">Select District</option>
                    </select>

                    @error('n_district_id')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

            </div> -->
            <div class="row g-4 mb-4">

                {{-- State --}}
                <div class="col-md-4">
                    <label for="n_state_id" class="form-label">
                        State *
                    </label>

                    <select id="n_state_id" name="n_state_id" class="form-select mandatory">

                        <option value="">Select State</option>

                        @foreach($states as $state)
                        <option value="{{ $state->n_state_id }}"
                            {{ old('n_state_id') == $state->n_state_id ? 'selected' : '' }}>
                            {{ $state->name }}
                        </option>
                        @endforeach

                    </select>

                    @error('n_state_id')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>


                {{-- District --}}
                <div class="col-md-4">
                    <label for="n_district_id" class="form-label">
                        District *
                    </label>

                    <select id="n_district_id" name="n_district_id" class="form-select mandatory">

                        <option value="">Select District</option>

                    </select>

                    @error('n_district_id')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Panchayath --}}
                <div class="col-md-4">
                    <label for="c_panchayath" class="form-label">Panchayath *</label>

                    <input type="text" id="c_panchayath" name="c_panchayath" value="{{ old('c_panchayath') }}"
                        maxlength="100" class="form-control mandatory" placeholder="Enter Panchayath">

                    @error('c_panchayath')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="n_district_id" class="form-label">
                        Latitude
                    </label>

                    <input type="text" id="latitude" id="latitude" name="latitude" value="{{ old('latitude') }}"
                        maxlength="255" class="form-control mandatory" placeholder="Enter Latitude">


                    @error('latitude')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="longitude" class="form-label">
                        Longitude
                    </label>

                    <input type="text" id="longitude" id="longitude" name="longitude" value="{{ old('longitude') }}"
                        maxlength="255" class="form-control mandatory" placeholder="Enter Longitude">


                    @error('longitude')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="openMapBtn" class="btn btn-primary" onclick="openMap()">
                        Select Location
                    </button>
                </div>
                <div id="map" style="height:400px; margin-top:20px; display:none;">
                </div>
            </div>

            {{-- GPS Location --}}
            <div class="field-group-title mt-5">
                <i class="ti ti-map-pin fs-5"></i>
                Franchise GPS Location
            </div>

            <div class="row g-4 mb-4">

                <div class="col-md-6">
                    <label for="latitude" class="form-label">
                        Latitude
                    </label>

                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" class="form-control"
                        placeholder="Latitude" readonly>

                    @error('latitude')
                    <div class="text-danger mt-1 fs-2">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="longitude" class="form-label">
                        Longitude
                    </label>

                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}"
                        class="form-control" placeholder="Longitude" readonly>

                    @error('longitude')
                    <div class="text-danger mt-1 fs-2">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

            </div>

            <div class="mb-4 d-flex align-items-center gap-2 flex-wrap">

                <button type="button" id="getLocationBtn" class="btn btn-outline-success">
                    <i class="ti ti-map-pin-search"></i>
                    Get Location from Address
                </button>

                <button type="button" id="selectLocationBtn" class="btn btn-outline-primary">
                    <i class="ti ti-map-pin"></i>
                    Select Location on Map
                </button>

                <span id="locationStatus" class="ms-2 text-muted"></span>

            </div>

            <div id="franchiseMap" style="display:none;width:100%;height:450px;border-radius:12px;margin-bottom:25px;">
            </div>

            <!-- Section 2: Communication -->
            <div class="field-group-title mt-5">
                <i class="ti ti-mail-forward fs-5"></i> Contact & Availability
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <label for="c_store_email" class="form-label">Email</label>
                    <input type="email" id="c_store_email" name="c_store_email" value="{{ old('c_store_email') }}"
                        class="form-control" placeholder="branch@spc.com">
                    @error('c_store_email')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="n_store_phone" class="form-label">Phone</label>
                    <input type="text" id="n_store_phone" name="n_store_phone" value="{{ old('n_store_phone') }}"
                        max-length="10" class="form-control" placeholder="Contact number">
                    @error('n_store_phone')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="c_store_status" class="form-label">Status *</label>
                    <select id="c_store_status" data-message="Please select Status" name="c_store_status"
                        class="form-select mandatory">
                        <option value="">Select Status</option>
                        <option value="Y" {{ old('c_store_status') === 'Y' ? 'selected' : '' }}>Active</option>
                        <option value="N" {{ old('c_store_status') === 'N' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('c_store_status')
                    <div class="text-danger mt-1 fs-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Enhanced Action Bar -->
            <div class="pt-4 border-top d-flex gap-3">
                <button type="submit" id="btn_create" class="btn buttonSpc btn-create-action">
                    <i class="ti ti-plus fs-4"></i> Create Franchise
                </button>
                <a href="{{ route('admin.franchises.index') }}" class="btn btn-outline-secondary"
                    style="--bs-btn-padding-y: 15px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
$(document).ready(function() {

    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    let franchiseMap = null;
    let franchiseMarker = null;


    /*
    |--------------------------------------------------------------------------
    | STATE → DISTRICT
    |--------------------------------------------------------------------------
    */

    $('#n_state_id').on('change', function() {

        let stateId = $(this).val();

        $('#n_district_id').html(
            '<option value="">Loading...</option>'
        );

        if (!stateId) {

            $('#n_district_id').html(
                '<option value="">Select District</option>'
            );

            return;
        }

        $.ajax({

            type: 'GET',

            url: "{{ route('admin.filterDistrict') }}",

            data: {
                state: stateId
            },

            dataType: 'json',

            success: function(response) {

                console.log(
                    'District response:',
                    response
                );

                $('#n_district_id').html(
                    '<option value="">Select District</option>'
                );

                if (
                    response.districts &&
                    response.districts.length > 0
                ) {

                    $.each(
                        response.districts,
                        function(index, district) {

                            $('#n_district_id').append(

                                '<option value="' +
                                district.id +
                                '">' +
                                district.district_name +
                                '</option>'

                            );

                        }
                    );

                } else {

                    $('#n_district_id').html(
                        '<option value="">No Districts Found</option>'
                    );

                }

            },

            error: function(xhr) {

                console.error(
                    'District loading failed:',
                    xhr.responseText
                );

                $('#n_district_id').html(
                    '<option value="">Unable to load districts</option>'
                );

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE LEAFLET MAP
    |--------------------------------------------------------------------------
    */

    function initializeMap(
        latitude = 10.8505,
        longitude = 76.2711,
        zoom = 8
    ) {

        $('#franchiseMap').show();


        /*
        |--------------------------------------------------------------------------
        | Create map only once
        |--------------------------------------------------------------------------
        */

        if (!franchiseMap) {

            franchiseMap = L.map(
                'franchiseMap'
            ).setView(
                [
                    latitude,
                    longitude
                ],
                zoom
            );


            /*
            |--------------------------------------------------------------------------
            | OpenStreetMap Tiles
            |--------------------------------------------------------------------------
            */

            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,

                    attribution: '&copy; OpenStreetMap contributors'
                }
            ).addTo(franchiseMap);


            /*
            |--------------------------------------------------------------------------
            | Manual map click
            |--------------------------------------------------------------------------
            */

            franchiseMap.on(
                'click',
                function(e) {

                    setLocation(
                        e.latlng.lat,
                        e.latlng.lng,
                        true
                    );

                }
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | Map already exists
            |--------------------------------------------------------------------------
            */

            franchiseMap.setView(
                [
                    latitude,
                    longitude
                ],
                zoom
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Fix Leaflet map size
        |--------------------------------------------------------------------------
        */

        setTimeout(
            function() {

                franchiseMap.invalidateSize();

            },
            300
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SET LOCATION
    |--------------------------------------------------------------------------
    */

    function setLocation(
        latitude,
        longitude,
        manual = false
    ) {

        latitude = parseFloat(
            latitude
        ).toFixed(7);

        longitude = parseFloat(
            longitude
        ).toFixed(7);


        /*
        |--------------------------------------------------------------------------
        | Set input values
        |--------------------------------------------------------------------------
        */

        $('#latitude').val(
            latitude
        );

        $('#longitude').val(
            longitude
        );


        /*
        |--------------------------------------------------------------------------
        | Marker position
        |--------------------------------------------------------------------------
        */

        const latLng = [

            parseFloat(latitude),

            parseFloat(longitude)

        ];


        /*
        |--------------------------------------------------------------------------
        | Create marker
        |--------------------------------------------------------------------------
        */

        if (franchiseMarker) {

            franchiseMarker.setLatLng(
                latLng
            );

        } else {

            franchiseMarker =
                L.marker(latLng)
                .addTo(franchiseMap);

        }


        /*
        |--------------------------------------------------------------------------
        | Move map
        |--------------------------------------------------------------------------
        */

        franchiseMap.setView(
            latLng,
            16
        );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (manual) {

            $('#locationStatus')
                .removeClass(
                    'text-muted text-danger'
                )
                .addClass(
                    'text-success'
                )
                .html(
                    '✓ Location manually selected'
                );

        } else {

            $('#locationStatus')
                .removeClass(
                    'text-muted text-danger'
                )
                .addClass(
                    'text-success'
                )
                .html(
                    '✓ Location found. Please verify the marker.'
                );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SELECT LOCATION ON MAP
    |--------------------------------------------------------------------------
    */

    $('#selectLocationBtn').on(
        'click',
        function() {

            initializeMap();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | GEOCODING FUNCTION
    |--------------------------------------------------------------------------
    */

    function searchLocation(
        query,
        callback
    ) {

        console.log(
            'Searching:',
            query
        );


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

            success: function(
                response
            ) {

                console.log(
                    'Search result:',
                    response
                );

                callback(
                    response
                );

            },

            error: function(
                xhr
            ) {

                console.error(
                    'Geocoding error:',
                    xhr.responseText
                );

                callback(
                    []
                );

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | GET LOCATION FROM ADDRESS
    |--------------------------------------------------------------------------
    */

    $('#getLocationBtn').on(
        'click',
        function() {


            /*
            |--------------------------------------------------------------------------
            | Get Address
            |--------------------------------------------------------------------------
            */

            const address =
                $('#c_store_address')
                .val()
                .trim();


            /*
            |--------------------------------------------------------------------------
            | Get State
            |--------------------------------------------------------------------------
            */

            const state =
                $('#n_state_id option:selected')
                .text()
                .trim();


            /*
            |--------------------------------------------------------------------------
            | Get District
            |--------------------------------------------------------------------------
            */

            const district =
                $('#n_district_id option:selected')
                .text()
                .trim();


            /*
            |--------------------------------------------------------------------------
            | Get Panchayath
            |--------------------------------------------------------------------------
            */

            const panchayath =
                $('#c_panchayath')
                .val()
                .trim();


            /*
            |--------------------------------------------------------------------------
            | VALIDATION
            |--------------------------------------------------------------------------
            */

            if (!address) {

                $('#locationStatus')
                    .removeClass(
                        'text-muted text-success'
                    )
                    .addClass(
                        'text-danger'
                    )
                    .text(
                        'Please enter the address first.'
                    );

                $('#c_store_address')
                    .focus();

                return;

            }


            if (
                !state ||
                state === 'Select State'
            ) {

                $('#locationStatus')
                    .removeClass(
                        'text-muted text-success'
                    )
                    .addClass(
                        'text-danger'
                    )
                    .text(
                        'Please select a state.'
                    );

                $('#n_state_id')
                    .focus();

                return;

            }


            if (
                !district ||
                district === 'Select District'
            ) {

                $('#locationStatus')
                    .removeClass(
                        'text-muted text-success'
                    )
                    .addClass(
                        'text-danger'
                    )
                    .text(
                        'Please select a district.'
                    );

                $('#n_district_id')
                    .focus();

                return;

            }


            if (!panchayath) {

                $('#locationStatus')
                    .removeClass(
                        'text-muted text-success'
                    )
                    .addClass(
                        'text-danger'
                    )
                    .text(
                        'Please enter the Panchayath.'
                    );

                $('#c_panchayath')
                    .focus();

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Button
            |--------------------------------------------------------------------------
            */

            const button =
                $('#getLocationBtn');


            button.prop(
                'disabled',
                true
            );


            button.html(
                '<i class="ti ti-loader-2"></i> Searching...'
            );


            $('#locationStatus')
                .removeClass(
                    'text-success text-danger'
                )
                .addClass(
                    'text-muted'
                )
                .text(
                    'Finding location...'
                );


            /*
            |--------------------------------------------------------------------------
            | SEARCH QUERIES
            |--------------------------------------------------------------------------
            |
            | Search from most specific → least specific.
            |
            */

            const searches = [

                /*
                |--------------------------------------------------------------
                | 1. Full Address
                |--------------------------------------------------------------
                */

                address +
                ', ' +
                panchayath +
                ', ' +
                district +
                ', ' +
                state +
                ', India',


                /*
                |--------------------------------------------------------------
                | 2. Address + District + State
                |--------------------------------------------------------------
                */

                address +
                ', ' +
                district +
                ', ' +
                state +
                ', India',


                /*
                |--------------------------------------------------------------
                | 3. Panchayath + District + State
                |--------------------------------------------------------------
                */

                panchayath +
                ', ' +
                district +
                ', ' +
                state +
                ', India',


                /*
                |--------------------------------------------------------------
                | 4. District + State
                |--------------------------------------------------------------
                */

                district +
                ', ' +
                state +
                ', India'

            ];


            /*
            |--------------------------------------------------------------------------
            | TRY SEARCH
            |--------------------------------------------------------------------------
            */

            function trySearch(
                index
            ) {


                /*
                |--------------------------------------------------------------------------
                | No more searches
                |--------------------------------------------------------------------------
                */

                if (
                    index >=
                    searches.length
                ) {

                    button.prop(
                        'disabled',
                        false
                    );

                    button.html(
                        '<i class="ti ti-map-pin-search"></i> Get Location from Address'
                    );


                    $('#locationStatus')
                        .removeClass(
                            'text-muted text-success'
                        )
                        .addClass(
                            'text-danger'
                        )
                        .html(
                            'Location not found. Please select the location manually on the map.'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Open map automatically
                    |--------------------------------------------------------------------------
                    */

                    initializeMap();


                    return;

                }


                const query =
                    searches[index];


                console.log(
                    'Trying search #' +
                    (index + 1) +
                    ':',
                    query
                );


                /*
                |--------------------------------------------------------------------------
                | Search
                |--------------------------------------------------------------------------
                */

                searchLocation(
                    query,
                    function(results) {


                        /*
                        |--------------------------------------------------------------------------
                        | Result found
                        |--------------------------------------------------------------------------
                        */

                        if (
                            results &&
                            results.length > 0
                        ) {


                            const result =
                                results[0];


                            const latitude =
                                parseFloat(
                                    result.lat
                                );


                            const longitude =
                                parseFloat(
                                    result.lon
                                );


                            console.log(
                                'Location found:',
                                result
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | Open map
                            |--------------------------------------------------------------------------
                            */

                            initializeMap(
                                latitude,
                                longitude,
                                16
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | Set marker
                            |--------------------------------------------------------------------------
                            */

                            setLocation(
                                latitude,
                                longitude,
                                false
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | Display found address
                            |--------------------------------------------------------------------------
                            */

                            let displayName =
                                result.display_name ||
                                'Location found';


                            $('#locationStatus')
                                .removeClass(
                                    'text-muted text-danger'
                                )
                                .addClass(
                                    'text-success'
                                )
                                .html(
                                    '✓ Location found. Please verify the marker on the map.'
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | Restore button
                            |--------------------------------------------------------------------------
                            */

                            button.prop(
                                'disabled',
                                false
                            );


                            button.html(
                                '<i class="ti ti-map-pin-search"></i> Get Location from Address'
                            );


                            return;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Try next query
                        |--------------------------------------------------------------------------
                        */

                        console.log(
                            'No result for:',
                            query
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Wait before next request
                        |--------------------------------------------------------------------------
                        |
                        | Important for Nominatim.
                        |
                        */

                        setTimeout(
                            function() {

                                trySearch(
                                    index + 1
                                );

                            },
                            1200
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | START SEARCH
            |--------------------------------------------------------------------------
            */

            trySearch(0);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT VALIDATION
    |--------------------------------------------------------------------------
    */

    $('#frm_create').on(
        'submit',
        function(e) {


            const latitude =
                $('#latitude')
                .val()
                .trim();


            const longitude =
                $('#longitude')
                .val()
                .trim();


            /*
            |--------------------------------------------------------------------------
            | Location required
            |--------------------------------------------------------------------------
            */

            if (
                !latitude ||
                !longitude
            ) {

                e.preventDefault();


                $('#locationStatus')
                    .removeClass(
                        'text-muted text-success'
                    )
                    .addClass(
                        'text-danger'
                    )
                    .text(
                        'Please select the franchise location on the map.'
                    );


                /*
                |--------------------------------------------------------------------------
                | Open map
                |--------------------------------------------------------------------------
                */

                initializeMap();


                return false;

            }

        }
    );

});
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
let map = null;
let marker = null;

const defaultLat = 10.8505;
const defaultLng = 76.2711;


/*
|--------------------------------------------------------------------------
| OPEN MAP
|--------------------------------------------------------------------------
*/

$('#openMapBtn').on('click', function() {

    $('#map').show();

    /*
    |--------------------------------------------------------------------------
    | Create map only once
    |--------------------------------------------------------------------------
    */

    if (!map) {

        map = L.map('map').setView(
            [defaultLat, defaultLng],
            9
        );

        /*
        |--------------------------------------------------------------------------
        | OpenStreetMap
        |--------------------------------------------------------------------------
        */

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }
        ).addTo(map);


        /*
        |--------------------------------------------------------------------------
        | SEARCH BOX
        |--------------------------------------------------------------------------
        */

        L.Control.geocoder({

                defaultMarkGeocode: false,

                placeholder: 'Search location...',

                errorMessage: 'Location not found'

            })
            .on('markgeocode', function(e) {

                const latlng = e.geocode.center;

                console.log(
                    'Selected Location:',
                    e.geocode.name
                );

                console.log(
                    'Latitude:',
                    latlng.lat
                );

                console.log(
                    'Longitude:',
                    latlng.lng
                );


                /*
                |--------------------------------------------------------------------------
                | Move map
                |--------------------------------------------------------------------------
                */

                map.setView(
                    latlng,
                    17
                );


                /*
                |--------------------------------------------------------------------------
                | Add / Move marker
                |--------------------------------------------------------------------------
                */

                setLocation(
                    latlng.lat,
                    latlng.lng
                );

            })
            .addTo(map);


        /*
        |--------------------------------------------------------------------------
        | CLICK MAP TO SELECT LOCATION
        |--------------------------------------------------------------------------
        */

        map.on('click', function(e) {

            setLocation(
                e.latlng.lat,
                e.latlng.lng
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Fix map rendering when inside hidden div
    |--------------------------------------------------------------------------
    */

    setTimeout(function() {

        map.invalidateSize();

    }, 200);

});


/*
|--------------------------------------------------------------------------
| SET LOCATION
|--------------------------------------------------------------------------
*/

function setLocation(latitude, longitude) {

    latitude = parseFloat(latitude);
    longitude = parseFloat(longitude);


    /*
    |--------------------------------------------------------------------------
    | Set input values
    |--------------------------------------------------------------------------
    */

    $('#latitude').val(
        latitude.toFixed(6)
    );

    $('#longitude').val(
        longitude.toFixed(6)
    );


    /*
    |--------------------------------------------------------------------------
    | Remove old marker
    |--------------------------------------------------------------------------
    */

    if (marker) {

        map.removeLayer(marker);

    }


    /*
    |--------------------------------------------------------------------------
    | Add new marker
    |--------------------------------------------------------------------------
    */

    marker = L.marker(
            [latitude, longitude], {
                draggable: true
            }
        )
        .addTo(map);


    /*
    |--------------------------------------------------------------------------
    | Marker popup
    |--------------------------------------------------------------------------
    */

    marker.bindPopup(
        '<b>Selected Location</b><br>' +
        'Latitude: ' + latitude.toFixed(6) +
        '<br>' +
        'Longitude: ' + longitude.toFixed(6)
    ).openPopup();


    /*
    |--------------------------------------------------------------------------
    | Allow dragging marker
    |--------------------------------------------------------------------------
    */

    marker.on('dragend', function(e) {

        const position =
            e.target.getLatLng();

        setLocation(
            position.lat,
            position.lng
        );

    });

}


/*
|--------------------------------------------------------------------------
| If old latitude / longitude exists
|--------------------------------------------------------------------------
*/

$(document).ready(function() {

    const oldLat = $('#latitude').val();
    const oldLng = $('#longitude').val();

    if (oldLat && oldLng) {

        $('#map').show();

        map = L.map('map').setView(
            [
                parseFloat(oldLat),
                parseFloat(oldLng)
            ],
            17
        );


        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }
        ).addTo(map);


        L.Control.geocoder({

                defaultMarkGeocode: false,

                placeholder: 'Search location...'

            })
            .on('markgeocode', function(e) {

                const latlng =
                    e.geocode.center;

                map.setView(
                    latlng,
                    17
                );

                setLocation(
                    latlng.lat,
                    latlng.lng
                );

            })
            .addTo(map);


        marker = L.marker(
            [
                parseFloat(oldLat),
                parseFloat(oldLng)
            ], {
                draggable: true
            }
        ).addTo(map);


        marker.on('dragend', function(e) {

            const position =
                e.target.getLatLng();

            setLocation(
                position.lat,
                position.lng
            );

        });


        setTimeout(function() {

            map.invalidateSize();

        }, 200);

    }

});
</script>
@endpush