{{--
    GPS capture block for the check-in and check-out forms.
    Drop it inside a <form>; it fills hidden latitude / longitude / accuracy fields from
    the browser's geolocation and shows the result. Included once per form; the script
    is only pushed once.
--}}
@php $requireGps = (bool) config('spc.field_log_require_gps'); @endphp

<div class="fl-gps" data-require="{{ $requireGps ? '1' : '0' }}"
     style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin:12px 0;padding:10px 14px;border:1px dashed #b7c9bb;border-radius:12px;background:rgba(47,125,79,.06);font-size:13px;">
    <input type="hidden" name="latitude" class="fl-gps-lat" value="{{ old('latitude') }}">
    <input type="hidden" name="longitude" class="fl-gps-lng" value="{{ old('longitude') }}">
    <input type="hidden" name="accuracy" class="fl-gps-acc" value="{{ old('accuracy') }}">
    <i class="ti ti-map-pin" style="font-size:18px;"></i>
    <span class="fl-gps-status" style="flex:1;min-width:180px;">Getting your location&hellip;</span>
    <button type="button" class="btn btn-sm btn-outline-secondary fl-gps-retry" style="display:none;">
        <i class="ti ti-refresh"></i> Retry
    </button>
</div>

@once
    @push('scripts')
    <script>
    (function () {
        var MAX_ACC = {{ (int) config('spc.field_log_max_accuracy_m', 200) }};

        function setStatus($box, cls, html) {
            $box.find('.fl-gps-status').css('color', cls === 'ok' ? '#1f7a3f' : (cls === 'warn' ? '#b45309' : (cls === 'bad' ? '#b91c1c' : ''))).html(html);
        }

        function locate($box) {
            var $retry = $box.find('.fl-gps-retry').hide();

            if (!navigator.geolocation) {
                setStatus($box, 'bad', 'This browser cannot share location.');
                $retry.hide();
                return;
            }
            if (window.isSecureContext === false) {
                setStatus($box, 'bad', 'Location needs a secure (https) connection.');
                return;
            }

            setStatus($box, '', 'Getting your location&hellip;');

            navigator.geolocation.getCurrentPosition(function (pos) {
                var c = pos.coords;
                $box.find('.fl-gps-lat').val(c.latitude.toFixed(7));
                $box.find('.fl-gps-lng').val(c.longitude.toFixed(7));
                $box.find('.fl-gps-acc').val(Math.round(c.accuracy || 0));

                var acc = Math.round(c.accuracy || 0);
                if (acc > MAX_ACC) {
                    setStatus($box, 'warn', '&#9888; Location captured but low accuracy (&plusmn;' + acc + ' m). Move outdoors and retry for a better fix.');
                    $retry.show();
                } else {
                    setStatus($box, 'ok', '&#10003; Location captured (&plusmn;' + acc + ' m).');
                }
            }, function (err) {
                var msg = err && err.code === 1
                    ? 'Location permission denied. Allow location access for this site, then retry.'
                    : 'Could not get your location. Check GPS / network and retry.';
                setStatus($box, $box.data('require') ? 'bad' : 'warn', '&#9888; ' + msg);
                $retry.show();
            }, { enableHighAccuracy: true, timeout: 15000, maximumAge: 30000 });
        }

        $(function () {
            $('.fl-gps').each(function () {
                var $box = $(this);
                $box.data('require', $box.data('require') === 1 || $box.attr('data-require') === '1');
                locate($box);
            });

            $(document).on('click', '.fl-gps-retry', function () { locate($(this).closest('.fl-gps')); });

            // The checkout form sits in a modal: refresh the fix each time it opens.
            $(document).on('shown.bs.modal', '.modal', function () {
                $(this).find('.fl-gps').each(function () { locate($(this)); });
            });

            $('.fl-gps').closest('form').on('submit', function (e) {
                var $box = $(this).find('.fl-gps');
                if ($box.data('require') && !$box.find('.fl-gps-lat').val()) {
                    e.preventDefault();
                    setStatus($box, 'bad', '&#9888; Location is required to continue. Allow access and retry.');
                    $box.find('.fl-gps-retry').show();
                }
            });
        });
    })();
    </script>
    @endpush
@endonce
