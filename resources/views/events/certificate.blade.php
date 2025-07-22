@extends('layouts.template')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg" id="certificate">
                <div class="card-body p-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <!-- Certificate Header -->
                    <div class="text-center mb-4">
                        <h1 class="display-4 mb-2" style="font-family: 'Georgia', serif;">Certificate of Achievement</h1>
                        <div style="border-bottom: 3px solid gold; width: 200px; margin: 0 auto;"></div>
                    </div>

                    <!-- Event Info -->
                    <div class="text-center mb-4">
                        <h3 class="mb-2">{{ $event->name }}</h3>
                        <p class="mb-1">{{ \Carbon\Carbon::parse($event->start_time)->format('F d, Y') }}</p>
                        <p class="mb-0">{{ $event->venue }}</p>
                    </div>

                    <!-- Certificate Body -->
                    <div class="text-center mb-4">
                        <p class="lead mb-3">This is to certify that</p>
                        <h2 class="display-5 mb-3" style="color: gold; font-family: 'Georgia', serif;">{{ $result->participant->name }}</h2>
                        <p class="lead mb-3">has successfully completed the event</p>
                    </div>

                    <!-- Results Details -->
                    <div class="row text-center mb-4">
                        <div class="col-md-3">
                            <div class="p-3 bg-white bg-opacity-10 rounded">
                                <h4 class="mb-1" style="color: gold;">{{ $result->position }}</h4>
                                <p class="mb-0">Overall Position</p>
                            </div>
                        </div>
                        @if($result->category)
                        <div class="col-md-3">
                            <div class="p-3 bg-white bg-opacity-10 rounded">
                                <h4 class="mb-1" style="color: gold;">{{ $result->category_position }}</h4>
                                <p class="mb-0">Category Position</p>
                            </div>
                        </div>
                        @endif
                        @if($result->finish_time)
                        <div class="col-md-3">
                            <div class="p-3 bg-white bg-opacity-10 rounded">
                                <h4 class="mb-1" style="color: gold;">{{ $result->finish_time }}</h4>
                                <p class="mb-0">Finish Time</p>
                            </div>
                        </div>
                        @endif
                        @if($result->category)
                        <div class="col-md-3">
                            <div class="p-3 bg-white bg-opacity-10 rounded">
                                <h4 class="mb-1" style="color: gold;">{{ $result->category }}</h4>
                                <p class="mb-0">Category</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-5">
                        <div class="row">
                            <div class="col-md-6">
                                <div style="border-top: 2px solid white; width: 200px; margin: 0 auto;">
                                    <p class="mt-2 mb-0">Event Organizer</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="border-top: 2px solid white; width: 200px; margin: 0 auto;">
                                    <p class="mt-2 mb-0">{{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
                                    <small>Certificate Date</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo/Watermark -->
                    <div class="text-center mt-4">
                        <small class="text-white-50">The Athlete X Limited</small>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <button onclick="printCertificate()" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-print me-2"></i>Print Certificate
                </button>
                <a href="{{ route('events.results', $event->slug) }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back to Results
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function printCertificate() {
    // Hide action buttons and print
    const buttons = document.querySelector('.text-center.mt-4');
    buttons.style.display = 'none';

    window.print();

    // Show buttons again after print dialog
    setTimeout(() => {
        buttons.style.display = 'block';
    }, 1000);
}

// Print styles
const style = document.createElement('style');
style.textContent = `
    @media print {
        body * {
            visibility: hidden;
        }
        #certificate, #certificate * {
            visibility: visible;
        }
        #certificate {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .container {
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
