@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
    @include('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'System',
        'heroIcon' => 'fa-solid fa-gear',
        'heroSummary' => 'Company profile and policy configuration — every change is audited.',
        'heroStats' => [
            [
                'label' => 'Settings',
                'icon' => 'fa-solid fa-sliders',
                'value' => count($settings),
            ],
        ],
    ])

    <div class="content">

        {{-- =========================================================
            SETTINGS
        ========================================================== --}}
        <div class="card" style="max-width:680px;">

            <div class="widget-head">
                <div class="wh-ico">
                    <i class="fa-solid fa-sliders"></i>
                </div>

                <div>
                    <h3>Policy &amp; payroll configuration</h3>
                    <p>
                        Every change here is written to the audit log.
                    </p>
                </div>
            </div>


            {{-- =====================================================
                EDIT SETTINGS
            ====================================================== --}}
            @can('hr-settings.edit')

                <form
                    method="POST"
                    action="{{ route('hr.settings.update') }}"
                >
                    @csrf

                    <div class="field-grid">

                        @foreach($settings as $s)

                            <div class="field">

                                <label>
                                    {{ $s['label'] }}
                                </label>

                                <input
                                    type="{{ $s['type'] }}"
                                    name="{{ $s['key'] }}"
                                    value="{{ old($s['key'], $s['value']) }}"
                                >

                                @error($s['key'])
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                        @endforeach

                    </div>


                    <div class="form-actions">

                        <button
                            type="submit"
                            class="btn-primary"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save settings
                        </button>

                    </div>

                </form>

            @else

                {{-- =================================================
                    VIEW ONLY
                ================================================== --}}
                <div class="field-grid">

                    @foreach($settings as $s)

                        <div class="field">

                            <label>
                                {{ $s['label'] }}
                            </label>

                            <input
                                type="{{ $s['type'] }}"
                                value="{{ $s['value'] }}"
                                disabled
                            >

                        </div>

                    @endforeach

                </div>

            @endcan

        </div>

    </div>
@endsection