@extends('admin.layouts.app')

@section('title', 'Import Results - ' . $event->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">Import Results for {{ $event->name }}</h4>
                        <small class="text-muted">Upload CSV or Excel file with event results</small>
                    </div>
                    <div>
                        <a href="{{ route('admin.events.results.download-sample') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-download"></i> Download Sample CSV
                        </a>
                        <a href="{{ route('admin.events.results.index', $event->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Results
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('admin.events.results.import', $event->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="file" class="form-label">Select CSV File</label>
                                    <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                                    <div class="form-text">Supported format: CSV only. Maximum size: 2MB</div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Import Results
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Import Instructions</h6>
                                    <ul class="small mb-0">
                                        <li>Download the sample CSV file to see the required format</li>
                                        <li>Include participant name or email to match existing registrations</li>
                                        <li>Position is required for all participants</li>
                                        <li>Times should be in HH:MM:SS format (e.g., 1:25:30)</li>
                                        <li>Use 'true' or 'false' for DNF/DSQ columns</li>
                                        <li>Category position will be calculated automatically</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Required CSV Columns:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Column</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                        <th>Example</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>position</code></td>
                                        <td><span class="badge bg-danger">Yes</span></td>
                                        <td>Overall finishing position</td>
                                        <td>1, 2, 3...</td>
                                    </tr>
                                    <tr>
                                        <td><code>participant_name</code></td>
                                        <td><span class="badge bg-warning">Either</span></td>
                                        <td>Participant's full name</td>
                                        <td>John Doe</td>
                                    </tr>
                                    <tr>
                                        <td><code>participant_email</code></td>
                                        <td><span class="badge bg-warning">Either</span></td>
                                        <td>Participant's email address</td>
                                        <td>john@email.com</td>
                                    </tr>
                                    <tr>
                                        <td><code>bib_number</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Race bib number</td>
                                        <td>101</td>
                                    </tr>
                                    <tr>
                                        <td><code>sex</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Gender (M/F)</td>
                                        <td>M, F</td>
                                    </tr>
                                    <tr>
                                        <td><code>category</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Age/division category</td>
                                        <td>Men 18-29</td>
                                    </tr>
                                    <tr>
                                        <td><code>finish_time</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Finish time in HH:MM:SS</td>
                                        <td>1:25:30</td>
                                    </tr>
                                    <tr>
                                        <td><code>chip_time</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Chip time in HH:MM:SS</td>
                                        <td>1:25:25</td>
                                    </tr>
                                    <tr>
                                        <td><code>dnf</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Did not finish (true/false)</td>
                                        <td>false</td>
                                    </tr>
                                    <tr>
                                        <td><code>dsq</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Disqualified (true/false)</td>
                                        <td>false</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
