@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Project</h1>
        <div class="sub">Update {{ $project->name }}.</div>
    </div>
    <a href="{{ route('projects.index') }}" class="btn btn-ghost">Back to list</a>
</div>

<div class="card" style="max-width:820px;">
    <div class="card-header"><h2>Project details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('projects.edit', $project->id) }}">
            @csrf
            <div class="form-grid">
                <div class="field">
                    <label>Project Name <span class="req">*</span></label>
                    <input class="input" type="text" name="name" value="{{ old('name', $project->name) }}" required>
                </div>
                <div class="field">
                    <label>Project Type</label>
                    <input class="input" type="text" name="type" value="{{ old('type', $project->type) }}" list="projectTypes">
                    <datalist id="projectTypes">
                        @foreach(\App\Models\Setting::projectTypes() as $pt)
                            <option value="{{ $pt }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="field">
                    <label>Client <span class="req">*</span></label>
                    <select class="select" name="client_id" required>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Project Value (৳) <span class="req">*</span></label>
                    <input class="input" type="number" step="0.01" name="project_value" value="{{ old('project_value', $project->project_value) }}" required>
                </div>
                <div class="field">
                    <label>Start Date</label>
                    <input class="input" type="date" name="start_date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}">
                </div>
                <div class="field">
                    <label>Estimated End Date</label>
                    <input class="input" type="date" name="end_date" value="{{ old('end_date', optional($project->end_date)->format('Y-m-d')) }}">
                </div>
                <div class="field col-span">
                    <label>Description</label>
                    <textarea class="textarea" rows="4" name="description">{{ old('description', $project->description) }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Update Project</button>
                <a href="{{ route('projects.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
            <div class="form-legend"><span class="req">*</span> Required field</div>
        </form>
    </div>
</div>
@endsection
