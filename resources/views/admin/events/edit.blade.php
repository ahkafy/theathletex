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

                                // Additional Fields Management
                                let fieldIndex = {{ $event->additional_fields ? count($event->additional_fields) : 0 }};

                                $('#addFieldBtn').click(function() {
                                    const fieldHtml = `
                                        <div class="card mb-3 additional-field" data-index="${fieldIndex}">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="form-label small">Field Label</label>
                                                        <input type="text" name="additional_fields[${fieldIndex}][label]" class="form-control form-control-sm" placeholder="e.g., Blood Group" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label small">Field Type</label>
                                                        <select name="additional_fields[${fieldIndex}][type]" class="form-select form-select-sm">
                                                            <option value="text">Text</option>
                                                            <option value="email">Email</option>
                                                            <option value="number">Number</option>
                                                            <option value="tel">Phone</option>
                                                            <option value="date">Date</option>
                                                            <option value="textarea">Textarea</option>
                                                            <option value="select">Dropdown</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label small">Options (for dropdown)</label>
                                                        <input type="text" name="additional_fields[${fieldIndex}][options]" class="form-control form-control-sm options-field" placeholder="A+,B+,O+,AB+" disabled>
                                                        <small class="text-muted">Comma-separated</small>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label small d-block">&nbsp;</label>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="additional_fields[${fieldIndex}][required]" class="form-check-input" value="1">
                                                            <label class="form-check-label small">Required</label>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger remove-field-btn mt-1">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    $('#additionalFieldsContainer').append(fieldHtml);
                                    fieldIndex++;
                                });

                                // Remove field
                                $(document).on('click', '.remove-field-btn', function() {
                                    $(this).closest('.additional-field').remove();
                                });

                                // Enable/disable options field based on type
                                $(document).on('change', 'select[name*="[type]"]', function() {
                                    const $card = $(this).closest('.additional-field');
                                    const $optionsField = $card.find('.options-field');

                                    if ($(this).val() === 'select') {
                                        $optionsField.prop('disabled', false).prop('required', true);
                                    } else {
                                        $optionsField.prop('disabled', true).prop('required', false).val('');
                                    }
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

                    <!-- Additional Registration Fields Section -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Additional Registration Fields (Optional)</label>
                        <p class="text-muted small">Add custom fields that participants will fill during registration</p>

                        <div id="additionalFieldsContainer">
                            @if($event->additional_fields)
                                @foreach($event->additional_fields as $index => $field)
                                    <div class="card mb-3 additional-field" data-index="{{ $index }}">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label small">Field Label</label>
                                                    <input type="text" name="additional_fields[{{ $index }}][label]" class="form-control form-control-sm" placeholder="e.g., Blood Group" value="{{ $field['label'] }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">Field Type</label>
                                                    <select name="additional_fields[{{ $index }}][type]" class="form-select form-select-sm">
                                                        <option value="text" {{ $field['type'] == 'text' ? 'selected' : '' }}>Text</option>
                                                        <option value="email" {{ $field['type'] == 'email' ? 'selected' : '' }}>Email</option>
                                                        <option value="number" {{ $field['type'] == 'number' ? 'selected' : '' }}>Number</option>
                                                        <option value="tel" {{ $field['type'] == 'tel' ? 'selected' : '' }}>Phone</option>
                                                        <option value="date" {{ $field['type'] == 'date' ? 'selected' : '' }}>Date</option>
                                                        <option value="textarea" {{ $field['type'] == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                                        <option value="select" {{ $field['type'] == 'select' ? 'selected' : '' }}>Dropdown</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">Options (for dropdown)</label>
                                                    <input type="text" name="additional_fields[{{ $index }}][options]" class="form-control form-control-sm options-field" placeholder="A+,B+,O+,AB+" value="{{ isset($field['options']) ? implode(',', $field['options']) : '' }}" {{ $field['type'] != 'select' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Comma-separated</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small d-block">&nbsp;</label>
                                                    <div class="form-check">
                                                        <input type="checkbox" name="additional_fields[{{ $index }}][required]" class="form-check-input" value="1" {{ ($field['required'] ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label small">Required</label>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-danger remove-field-btn mt-1">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary" id="addFieldBtn">
                            <i class="bi bi-plus-circle"></i> Add Field
                        </button>
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
