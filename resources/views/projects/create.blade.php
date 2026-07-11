@extends('layouts.admin')

@section('title', 'Add Project')

@section('content')
<div class="page-header">
    <div>
        <h1>Add Project</h1>
        <div class="sub">Create a client project and capture the deal value.</div>
    </div>
    <a href="{{ route('projects.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:820px;">
    <div class="card-header"><h2>Project details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('projects.create') }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>Project Name <span class="req">*</span></label>
                    <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. ERP Web Portal" required>
                </div>
                <div class="field">
                    <label>Project Type</label>
                    <input class="input" type="text" name="type" value="{{ old('type') }}" placeholder="e.g. Software Project" list="projectTypes">
                    <datalist id="projectTypes">
                        @foreach(\App\Models\Setting::projectTypes() as $pt)
                            <option value="{{ $pt }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="field">
                    <label>Client <span class="req">*</span></label>
                    <select class="select" name="client_id" required>
                        <option value="">-- Select Client --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Project Value (৳) <span class="req">*</span></label>
                    <input class="input" type="number" step="0.01" name="project_value" value="{{ old('project_value', \App\Models\Setting::project()['default_project_value']) }}" placeholder="200000" required>
                </div>
                <div class="field">
                    <label>Start Date</label>
                    <input class="input" type="date" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="field">
                    <label>Estimated End Date</label>
                    <input class="input" type="date" name="end_date" value="{{ old('end_date') }}">
                </div>
                <div class="field">
                    <label>Project Status</label>
                    <select class="select" name="status">
                        @foreach(\App\Models\Project::statuses() as $st)
                            <option value="{{ $st }}" {{ old('status', 'Pending') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                    <span class="hint">Shown to the client on the tracking page.</span>
                </div>
                <div class="field col-span">
                    <label>Description</label>
                    <textarea class="textarea" rows="4" name="description" placeholder="Project details and scope...">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Save Project</button>
                <a href="{{ route('projects.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endsection
