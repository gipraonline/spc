@extends('layouts.app')

@section('topbarTitle', $editing ? 'Edit Task' : 'Assign Task')

@section('content')
<style>
.tk-card{background:#fff;border:1px solid rgba(18,58,40,.1);border-radius:16px;box-shadow:0 6px 20px -14px rgba(18,58,40,.25);}
.tk-people{max-height:260px;overflow:auto;border:1px solid #E3EDE3;border-radius:12px;padding:8px 12px;background:#FAFDF8;}
.tk-people label{display:flex;gap:8px;align-items:center;padding:4px 0;cursor:pointer;}
.tk-prio input{display:none;}
.tk-prio label{border:1.5px solid #E3EDE3;border-radius:12px;padding:9px 18px;font-weight:600;cursor:pointer;color:#5F7A6C;}
.tk-prio input:checked + label{color:#fff;border-color:transparent;}
.tk-prio .p-low:checked + label{background:#6c757d;} .tk-prio .p-medium:checked + label{background:#0aa2c0;}
.tk-prio .p-high:checked + label{background:#e0a21c;} .tk-prio .p-urgent:checked + label{background:#c0392b;}
</style>

<div class="mb-3">
    <a href="{{ route('admin.tasks.index') }}" class="text-decoration-none" style="color:#1F5C2E"><i class="bi bi-arrow-left"></i> Back to tasks</a>
    <h4 class="fw-semibold mt-1 mb-0" style="color:#1F5C2E">{{ $editing ? 'Edit task' : 'Assign a task' }}</h4>
</div>

@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form method="POST" action="{{ $editing ? route('admin.tasks.update', $task) : route('admin.tasks.store') }}" class="tk-card p-4">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-semibold">Task title <span class="text-danger">*</span></label>
            <input type="text" name="title" maxlength="200" class="form-control" value="{{ old('title', $task->title) }}" placeholder="e.g. Submit monthly stock report" required>
        </div>

        <div class="col-12">
            <label class="form-label fw-semibold">Details</label>
            <textarea name="description" rows="4" maxlength="5000" class="form-control" placeholder="What needs to be done, any instructions or expected output">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="col-md-7">
            <label class="form-label fw-semibold d-block">Priority <span class="text-danger">*</span></label>
            <div class="tk-prio d-flex flex-wrap gap-2">
                @foreach(\App\Models\Task::PRIORITIES as $key => $label)
                    <span>
                        <input type="radio" name="priority" id="p-{{ $key }}" class="p-{{ $key }}" value="{{ $key }}" @checked(old('priority', $task->priority) === $key)>
                        <label for="p-{{ $key }}">{{ $label }}</label>
                    </span>
                @endforeach
            </div>
        </div>

        <div class="col-md-5">
            <label class="form-label fw-semibold">Due date</label>
            <input type="date" name="due_date" class="form-control" @unless($editing) min="{{ today()->toDateString() }}" @endunless
                   value="{{ old('due_date', optional($task->due_date)->toDateString()) }}">
        </div>

        @if($editing)
            <div class="col-12">
                <div class="alert alert-light border mb-0">
                    <b>Department:</b> {{ $departments[$task->department_id] ?? '—' }} ·
                    <b>Assigned to:</b> {{ $task->assignees()->count() }} employee(s).
                    <span class="text-muted">The department and people cannot be changed after the task is assigned.</span>
                </div>
            </div>
        @else
            <div class="col-md-6">
                <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                <select name="department_id" id="tkDept" class="form-select" required>
                    <option value="">Select department</option>
                    @foreach($departments as $id => $name)
                        <option value="{{ $id }}" @selected(old('department_id') == $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold d-block">Assign to</label>
                <div class="d-flex flex-column gap-1">
                    <label><input type="radio" name="assign_mode" value="all" @checked(old('assign_mode', 'all') === 'all')> Everyone in the department <span id="tkCount" class="text-muted small"></span></label>
                    <label><input type="radio" name="assign_mode" value="pick" @checked(old('assign_mode') === 'pick')> Only selected people</label>
                </div>
            </div>

            <div class="col-12" id="tkPickBox" style="display:none">
                <div class="d-flex justify-content-between mb-1">
                    <label class="form-label fw-semibold mb-0">Choose employees</label>
                    <span class="small"><a href="#" id="tkAll">Select all</a> · <a href="#" id="tkNone">Clear</a></span>
                </div>
                <div class="tk-people" id="tkPeople"><span class="text-muted small">Choose a department first.</span></div>
            </div>
        @endif
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn buttonSpc">{{ $editing ? 'Save changes' : 'Assign task' }}</button>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

@unless($editing)
@push('scripts')
<script>
(function () {
    const dept = document.getElementById('tkDept');
    const people = document.getElementById('tkPeople');
    const pickBox = document.getElementById('tkPickBox');
    const count = document.getElementById('tkCount');
    const url = @json(route('admin.tasks.department-employees', '__ID__'));
    const oldIds = @json(array_map('intval', (array) old('employee_ids', [])));

    function mode() { return document.querySelector('input[name=assign_mode]:checked').value; }
    function togglePick() { pickBox.style.display = mode() === 'pick' ? 'block' : 'none'; }

    function load() {
        if (!dept.value) { people.innerHTML = '<span class="text-muted small">Choose a department first.</span>'; count.textContent = ''; return; }
        people.innerHTML = '<span class="text-muted small">Loading…</span>';
        fetch(url.replace('__ID__', dept.value), { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(list => {
                count.textContent = '(' + list.length + ' active)';
                if (!list.length) { people.innerHTML = '<span class="text-danger small">No active employees in this department.</span>'; return; }
                people.innerHTML = '';
                list.forEach(e => {
                    const label = document.createElement('label');
                    const box = document.createElement('input');
                    box.type = 'checkbox'; box.name = 'employee_ids[]'; box.value = e.id;
                    box.checked = oldIds.includes(e.id);
                    label.appendChild(box);
                    label.appendChild(document.createTextNode(' ' + e.name + (e.code ? ' (' + e.code + ')' : '')));
                    people.appendChild(label);
                });
            })
            .catch(() => { people.innerHTML = '<span class="text-danger small">Could not load employees.</span>'; });
    }

    dept.addEventListener('change', load);
    document.querySelectorAll('input[name=assign_mode]').forEach(r => r.addEventListener('change', togglePick));
    document.getElementById('tkAll').addEventListener('click', e => { e.preventDefault(); people.querySelectorAll('input[type=checkbox]').forEach(b => b.checked = true); });
    document.getElementById('tkNone').addEventListener('click', e => { e.preventDefault(); people.querySelectorAll('input[type=checkbox]').forEach(b => b.checked = false); });

    togglePick();
    if (dept.value) load();
})();
</script>
@endpush
@endunless
@endsection
