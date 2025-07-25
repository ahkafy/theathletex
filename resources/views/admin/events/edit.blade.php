@extends('admin.layouts.admin')

@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="text-center">Edit Event</h1>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Event: {{ $event->name }}</div>
            <div class="card-body">
                <form action="{{ route('admin.events.update', $event->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Event Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $event->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control summernote @error('description') is-invalid @enderror" id="description" rows="3">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @push('styles')
                        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
                    @endpush
                    @push('scripts')
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('.summernote').summernote({
                                    height: 200
                                });

                                // Dynamic fields functionality
                                let fieldIndex = {{ $event->dynamic_fields_config ? count($event->dynamic_fields_config) : 0 }};

                                $('#add-dynamic-field').click(function() {
                                    const fieldHtml = `
                                        <div class="border p-3 mb-2 dynamic-field-item">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Field Label</label>
                                                    <input type="text" name="dynamic_fields[${fieldIndex}][label]" class="form-control" placeholder="e.g., School Name">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Field Type</label>
                                                    <select name="dynamic_fields[${fieldIndex}][type]" class="form-select">
                                                        <option value="text">Text</option>
                                                        <option value="email">Email</option>
                                                        <option value="number">Number</option>
                                                        <option value="select">Dropdown</option>
                                                        <option value="textarea">Textarea</option>
                                                        <option value="date">Date</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Help Text/Info</label>
                                                    <input type="text" name="dynamic_fields[${fieldIndex}][info]" class="form-control" placeholder="e.g., Enter your current school name">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Options (for dropdown)</label>
                                                    <input type="text" name="dynamic_fields[${fieldIndex}][options]" class="form-control" placeholder="Option1,Option2,Option3">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Required</label>
                                                    <div class="form-check">
                                                        <input type="checkbox" name="dynamic_fields[${fieldIndex}][required]" class="form-check-input" value="1">
                                                        <label class="form-check-label">Required</label>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-danger mt-1 remove-field">Remove</button>
                                                </div>
                                            </div>
                                        </div>`;
                                    $('#dynamic-fields-container').append(fieldHtml);
                                    fieldIndex++;
                                });

                                $(document).on('click', '.remove-field', function() {
                                    $(this).closest('.dynamic-field-item').remove();
                                });
                            });
                        </script>
                    @endpush
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" value="{{ old('start_time', $event->start_time ? date('Y-m-d\TH:i', strtotime($event->start_time)) : '') }}" required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" value="{{ old('end_time', $event->end_time ? date('Y-m-d\TH:i', strtotime($event->end_time)) : '') }}">
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" id="capacity" value="{{ old('capacity', $event->capacity) }}" min="1">
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="cover_photo" class="form-label">Cover Photo</label>
                        @if($event->cover_photo)
                            <div class="mb-2">
                                <img src="{{ asset($event->cover_photo) }}" alt="Current Cover Photo" style="width: 100px; height: 60px; object-fit: cover;" class="rounded">
                                <small class="text-muted d-block">Current cover photo</small>
                            </div>
                        @endif
                        <input type="file" name="cover_photo" class="form-control @error('cover_photo') is-invalid @enderror" id="cover_photo" accept="image/*">
                        <small class="text-muted">Leave empty to keep current photo</small>
                        @error('cover_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="venue" class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control @error('venue') is-invalid @enderror" id="venue" value="{{ old('venue', $event->venue) }}">
                        @error('venue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" id="status" required>
                            <option value="">Select status</option>
                            <option value="scheduled" {{ old('status', $event->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="open" {{ old('status', $event->status) == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ old('status', $event->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="complete" {{ old('status', $event->status) == 'complete' ? 'selected' : '' }}>Complete</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dynamic Fields Configuration -->
                    <div class="mb-3">
                        <label class="form-label">Dynamic Fields for Registration</label>
                        <div id="dynamic-fields-container">
                            @if($event->dynamic_fields_config && count($event->dynamic_fields_config) > 0)
                                @foreach($event->dynamic_fields_config as $index => $field)
                                    <div class="border p-3 mb-2 dynamic-field-item">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Field Label</label>
                                                <input type="text" name="dynamic_fields[{{ $index }}][label]" class="form-control" placeholder="e.g., School Name" value="{{ $field['label'] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Field Type</label>
                                                <select name="dynamic_fields[{{ $index }}][type]" class="form-select">
                                                    <option value="text" {{ ($field['type'] ?? '') == 'text' ? 'selected' : '' }}>Text</option>
                                                    <option value="email" {{ ($field['type'] ?? '') == 'email' ? 'selected' : '' }}>Email</option>
                                                    <option value="number" {{ ($field['type'] ?? '') == 'number' ? 'selected' : '' }}>Number</option>
                                                    <option value="select" {{ ($field['type'] ?? '') == 'select' ? 'selected' : '' }}>Dropdown</option>
                                                    <option value="textarea" {{ ($field['type'] ?? '') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                                    <option value="date" {{ ($field['type'] ?? '') == 'date' ? 'selected' : '' }}>Date</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Help Text/Info</label>
                                                <input type="text" name="dynamic_fields[{{ $index }}][info]" class="form-control" placeholder="e.g., Enter your current school name" value="{{ $field['info'] ?? '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Options (for dropdown)</label>
                                                <input type="text" name="dynamic_fields[{{ $index }}][options]" class="form-control" placeholder="Option1,Option2,Option3" value="{{ isset($field['options']) && is_array($field['options']) ? implode(',', $field['options']) : '' }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Required</label>
                                                <div class="form-check">
                                                    <input type="checkbox" name="dynamic_fields[{{ $index }}][required]" class="form-check-input" value="1" {{ ($field['required'] ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Required</label>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger mt-1 remove-field">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="border p-3 mb-2 dynamic-field-item">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Field Label</label>
                                            <input type="text" name="dynamic_fields[0][label]" class="form-control" placeholder="e.g., School Name">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Field Type</label>
                                            <select name="dynamic_fields[0][type]" class="form-select">
                                                <option value="text">Text</option>
                                                <option value="email">Email</option>
                                                <option value="number">Number</option>
                                                <option value="select">Dropdown</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="date">Date</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Help Text/Info</label>
                                            <input type="text" name="dynamic_fields[0][info]" class="form-control" placeholder="e.g., Enter your current school name">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Options (for dropdown)</label>
                                            <input type="text" name="dynamic_fields[0][options]" class="form-control" placeholder="Option1,Option2,Option3">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Required</label>
                                            <div class="form-check">
                                                <input type="checkbox" name="dynamic_fields[0][required]" class="form-check-input" value="1">
                                                <label class="form-check-label">Required</label>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-danger mt-1 remove-field">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" id="add-dynamic-field">Add Field</button>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Event</button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
