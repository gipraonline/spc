@extends('layouts.app')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

<style>
/* ===== Add / Edit Franchise — same visual language as the Employee Records module ===== */
.employee-page.efp {
    --brand: #4E7A33;
    --brand-strong: #0E5239;
    --brand-ink: #1F3D14;
    --brand-bright: #5E8D3D;
    --brand-glow: rgba(94, 141, 61, .35);
    --brand-soft: #E4F3EB;
    --brand-softer: #F2F9F5;
    --line: rgba(18, 58, 40, 0.13);
    --line-soft: rgba(18, 58, 40, 0.07);
    --text: #22352C;
    --text-muted: #61756B;
    --bad: #C23A3A;
    --shadow-sm: 0 1px 2px rgba(10, 61, 44, .05);
    --font-head: 'Kanit', sans-serif;
    --font-body: 'Outfit', sans-serif;
    padding: 24px clamp(16px, 3vw, 32px) 8px;
    font-family: var(--font-body);
    color: var(--text);
}

/* Page heading */
.efp .employee-page-heading { margin-bottom: 18px; }
.efp .employee-page-heading h2 {
    margin: 0;
    font-family: var(--font-head);
    font-weight: 600;
    font-size: 21px;
    color: var(--brand-ink);
    display: flex;
    align-items: center;
    gap: 10px;
}
.efp .employee-page-heading h2 i {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: var(--brand-soft);
    color: var(--brand);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}
.efp .employee-page-heading p { margin: 5px 0 0; color: var(--text-muted); font-size: 13px; }

/* Split card */
.efp-card {
    display: grid;
    grid-template-columns: 300px minmax(0, 1fr);
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

/* Left panel */
.efp-side {
    background: linear-gradient(160deg, #0E5239 0%, #1F5C2E 55%, #4E7A33 125%);
    color: #fff;
    padding: 28px 22px;
}
.efp-side-inner { position: sticky; top: 24px; }
.efp-side-ico {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: rgba(255, 255, 255, .14);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-left: 4px;
}
.efp-side h3 {
    margin: 16px 4px 6px;
    font-family: var(--font-head);
    font-size: 20px;
    font-weight: 600;
    line-height: 1.25;
    color: #fff;
    word-break: break-word;
}
.efp-side p { margin: 0 4px; font-size: 13px; line-height: 1.55; color: rgba(255, 255, 255, .78); }
.efp-steps { margin-top: 22px; display: grid; gap: 2px; }
.efp-step {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 8px 10px;
    border-radius: 10px;
    font-size: 13px;
    color: rgba(255, 255, 255, .9);
    text-decoration: none;
}
.efp-step:hover { background: rgba(255, 255, 255, .09); color: #fff; }
.efp-step .num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .16);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    flex-shrink: 0;
}

/* Form body */
.efp-body { padding: 26px 30px 0; background: #fff; min-width: 0; margin: 0; }
.efp-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: var(--brand-softer);
    border: 1px solid var(--line-soft);
    color: var(--brand-ink);
    border-radius: 12px;
    padding: 11px 14px;
    font-size: 12.5px;
    line-height: 1.5;
    margin-bottom: 22px;
}
.efp-note i { color: var(--brand); font-size: 16px; margin-top: 1px; }
.efp-alert {
    background: #FBE7E4;
    border: 1px solid rgba(194, 58, 58, .25);
    color: #942B2B;
    border-radius: 12px;
    padding: 11px 14px 11px 18px;
    font-size: 12.5px;
    margin-bottom: 22px;
}
.efp-alert ul { margin: 0; padding-left: 16px; }

.efp-section {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: var(--font-head);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--brand);
    margin: 4px 0 14px;
    scroll-margin-top: 20px;
}
.efp-section i { font-size: 16px; }
.efp-section::after { content: ''; flex: 1; height: 1px; background: var(--line-soft); }

.efp-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px 18px;
    margin-bottom: 26px;
}
.efp-field { min-width: 0; }
.efp-field.full { grid-column: 1 / -1; }
.efp-field > label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    color: #3B5246;
    margin-bottom: 6px;
}
.efp-field > label i { color: var(--brand); font-size: 15px; }

.efp-field .form-control,
.efp-field .form-select {
    height: 42px;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 0 12px;
    font-family: var(--font-body);
    font-size: 13.5px;
    font-weight: 400;
    color: var(--text);
    background-color: #FBFDFC;
    box-shadow: none;
    transition: border-color .2s, box-shadow .2s, background-color .2s;
}
.efp-field .form-select { padding-right: 34px; }
.efp-field textarea.form-control { height: auto; min-height: 92px; padding: 10px 12px; resize: vertical; }
.efp-field .form-control::placeholder { color: #98A9A0; }
.efp-field .form-control:focus,
.efp-field .form-select:focus {
    border-color: var(--brand-bright);
    background-color: #fff;
    box-shadow: 0 0 0 3.5px rgba(94, 141, 61, .14);
    outline: 0;
}
.efp-field .form-control:disabled,
.efp-field .form-control[readonly] {
    background-color: #F1F5F2;
    color: var(--text-muted);
    cursor: not-allowed;
}
.efp-field .form-control.is-invalid,
.efp-field .form-select.is-invalid { border-color: var(--bad); }
.efp-hint { display: block; margin-top: 5px; font-size: 11.5px; color: var(--text-muted); }
.efp-err { margin-top: 5px; font-size: 12px; }

/* Footer */
.efp-foot {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    padding: 16px 0 22px;
    border-top: 1px solid var(--line-soft);
}
.efp-hint-secure {
    margin-right: auto;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-muted);
}
.efp-btn {
    height: 41px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0 18px;
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid transparent;
    line-height: 1;
}
.efp-btn-secondary { background: var(--brand-softer); color: var(--brand); border-color: var(--line); }
.efp-btn-secondary:hover { background: var(--brand-soft); color: var(--brand-strong); }
.efp-btn-primary {
    background: linear-gradient(135deg, #5E8D3D, #1F5C2E);
    color: #fff;
    box-shadow: 0 10px 20px -10px var(--brand-glow);
}
.efp-btn-primary:hover { filter: brightness(1.07); color: #fff; }

@media (max-width: 992px) {
    .efp-card { grid-template-columns: 1fr; }
    .efp-side { padding: 22px 20px; }
    .efp-side-inner { position: static; }
    .efp-steps { grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); }
}
@media (max-width: 640px) {
    .efp-grid { grid-template-columns: 1fr; }
    .efp-body { padding: 20px 18px 0; }
}

/* 6-column grid so 2 / 3 / 6 wide fields can share rows */
.efp-grid-6 { grid-template-columns: repeat(6, minmax(0, 1fr)); }
.efp-grid-6 > * { grid-column: span 6; }
.efp-grid-6 > .span-3 { grid-column: span 3; }
.efp-grid-6 > .span-2 { grid-column: span 2; }
.efp-err:empty { display: none; }
.efp-field-action { display: flex; align-items: flex-end; }
.efp-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.efp .location-status { font-size: 12.5px; font-weight: 500; }

/* Leaflet map containers (ids used by the page scripts) */
#map,
#franchiseMap {
    width: 100%;
    height: 400px;
    border: 1px solid var(--line);
    border-radius: 14px;
    overflow: hidden;
}
.leaflet-control-geocoder { width: 300px; }
.leaflet-control-geocoder-form input { width: 250px; }

@media (max-width: 1200px) {
    .efp-grid-6 > .span-2 { grid-column: span 3; }
}
@media (max-width: 640px) {
    .efp-grid-6 > *,
    .efp-grid-6 > .span-2,
    .efp-grid-6 > .span-3 { grid-column: 1 / -1; }
}
</style>
@endpush

@section('content')
<div class="employee-page efp">
    <div class="employee-page-heading">
        <h2><i class="ti ti-building-store"></i>Add Franchise</h2>
        <p>Fill in the details below to add a new franchise.</p>
    </div>

    <div class="efp-card">
        <aside class="efp-side">
            <div class="efp-side-inner">
                <div class="efp-side-ico"><i class="ti ti-building-store"></i></div>
                <h3>Set up a franchise</h3>
                <p>Create the franchise record with its owner, location and contact details.</p>
                <div class="efp-steps">
                    <a href="#sec-identity" class="efp-step"><span class="num">1</span>Identity & location</a>
                    <a href="#sec-gps" class="efp-step"><span class="num">2</span>Franchise GPS location</a>
                    <a href="#sec-contact" class="efp-step"><span class="num">3</span>Contact & availability</a>
                </div>
            </div>
        </aside>

        <form id="frm_create" method="POST" action="{{ route('admin.franchises.store') }}" class="efp-body">
            @csrf

            <div class="efp-note"><i class="ti ti-info-circle"></i>Use Select Location to pin the franchise on the map and fill in its latitude and longitude.</div>

            <!-- Section 1: Record Identity -->
            <div class="efp-section" id="sec-identity"><i class="ti ti-id-badge-2"></i>Identity & Location</div>
            <div class="efp-grid efp-grid-6">
                <div class="efp-field span-3">
                    <label for="c_store_code"><i class="ti ti-barcode"></i>Franchise Code *</label>
                    <input type="text" id="c_store_code" data-message="Enter valid Store Code" name="c_store_code"
                        value="{{ old('c_store_code') }}" max-length="20" class="form-control mandatory"
                        placeholder="e.g. SPC-001">
                    @error('c_store_code')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field span-3">
                    <label for="c_store_name"><i class="ti ti-building-store"></i>Franchise Name *</label>
                    <input type="text" id="c_store_name" data-message="Please enter Name" name="c_store_name"
                        value="{{ old('c_store_name') }}" maxlength="100" pattern="[A-Za-z0-9\s\-]+"
                        class="form-control mandatory" placeholder="Legal Franchise name">
                    @error('c_store_name')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field">
                    <label for="c_owner_name"><i class="ti ti-user"></i>Owner Name *</label>
                    <input type="text" id="c_owner_name" name="c_owner_name" value="{{ old('c_owner_name') }}"
                        maxlength="100" class="form-control mandatory" placeholder="Enter Owner Name">
                    @error('c_owner_name')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field">
                    <label for="c_store_address"><i class="ti ti-map-pin"></i>Address</label>
                    <input type="text" id="c_store_address" name="c_store_address" value="{{ old('c_store_address') }}"
                        maxlength="255" class="form-control" placeholder="Street, Building, Area...">
                    @error('c_store_address')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field span-2">
                    <label for="n_state_id"><i class="ti ti-map-2"></i>State *</label>
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
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field span-2">
                    <label for="n_district_id"><i class="ti ti-map"></i>District *</label>
                    <select id="n_district_id" name="n_district_id" class="form-select mandatory">
                        <option value="">Select District</option>
                    </select>
                    @error('n_district_id')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field span-2">
                    <label for="c_panchayath"><i class="ti ti-building-community"></i>Panchayath *</label>
                    <input type="text" id="c_panchayath" name="c_panchayath" value="{{ old('c_panchayath') }}"
                        maxlength="100" class="form-control mandatory" placeholder="Enter Panchayath">
                    @error('c_panchayath')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- GPS Location -->
            <div class="efp-section" id="sec-gps"><i class="ti ti-map-pin"></i>Franchise GPS Location</div>
            <div class="efp-grid efp-grid-6">
                <div class="efp-field span-2">
                    <label for="latitude"><i class="ti ti-current-location"></i>Latitude</label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}"
                        maxlength="255" class="form-control mandatory" placeholder="Enter Latitude">
                    @error('latitude')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field span-2">
                    <label for="longitude"><i class="ti ti-current-location"></i>Longitude</label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}"
                        maxlength="255" class="form-control mandatory" placeholder="Enter Longitude">
                    @error('longitude')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field-action span-2">
                    <button type="button" id="openMapBtn" class="efp-btn efp-btn-secondary" onclick="openMap()">
                        <i class="ti ti-map-pin"></i>Select Location
                    </button>
                </div>

                <div id="map" style="display:none;"></div>
            </div>

            <!-- Section 2: Communication -->
            <div class="efp-section" id="sec-contact"><i class="ti ti-mail-forward"></i>Contact & Availability</div>
            <div class="efp-grid efp-grid-6">
                <div class="efp-field span-2">
                    <label for="c_store_email"><i class="ti ti-mail"></i>Email</label>
                    <input type="email" id="c_store_email" name="c_store_email" value="{{ old('c_store_email') }}"
                        class="form-control" placeholder="branch@spc.com">
                    @error('c_store_email')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field span-2">
                    <label for="n_store_phone"><i class="ti ti-phone"></i>Phone</label>
                    <input type="text" id="n_store_phone" name="n_store_phone" value="{{ old('n_store_phone') }}"
                        max-length="10" class="form-control" placeholder="Contact number">
                    @error('n_store_phone')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>

                <div class="efp-field span-2">
                    <label for="c_store_status"><i class="ti ti-circle-half-2"></i>Status *</label>
                    <select id="c_store_status" data-message="Please select Status" name="c_store_status"
                        class="form-select mandatory">
                        <option value="">Select Status</option>
                        <option value="Y" {{ old('c_store_status') === 'Y' ? 'selected' : '' }}>Active</option>
                        <option value="N" {{ old('c_store_status') === 'N' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('c_store_status')
                    <div class="text-danger efp-err">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="efp-foot">
                <span class="efp-hint-secure"><i class="ti ti-asterisk"></i>Fields marked * are required</span>
                <a href="{{ route('admin.franchises.index') }}" class="efp-btn efp-btn-secondary">Cancel</a>
                <button type="submit" id="btn_create" class="efp-btn efp-btn-primary">
                    <i class="ti ti-plus"></i>Create Franchise
                </button>
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