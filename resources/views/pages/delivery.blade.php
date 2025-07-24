@extends('layouts.app')

@section('title', 'Delivery Policy - TheAthleteX')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Delivery Policy</h1>
                <p class="lead text-muted">Information about event materials and digital deliverables</p>
                <small class="text-muted">Last updated: {{ date('F d, Y') }}</small>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Overview</h3>
                        <p class="text-muted">
                            TheAthleteX primarily operates as a digital platform for athletic event registration and management.
                            Our "delivery" services encompass digital certificates, event confirmations, and any physical items
                            that may be associated with specific events. This policy outlines how we handle the distribution of
                            event-related materials and digital deliverables.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Digital Deliverables</h3>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-4 h-100">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-certificate fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="text-center">Digital Certificates</h5>
                                    <ul class="text-muted">
                                        <li>Available immediately after event completion</li>
                                        <li>Downloadable from your participant dashboard</li>
                                        <li>High-quality PDF format</li>
                                        <li>Personalized with your achievement details</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-4 h-100">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-envelope fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="text-center">Event Communications</h5>
                                    <ul class="text-muted">
                                        <li>Registration confirmations (instant)</li>
                                        <li>Event reminders (24-48 hours before)</li>
                                        <li>Result notifications (within 24 hours)</li>
                                        <li>Important updates (as needed)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Physical Event Materials</h3>
                        <p class="text-muted mb-3">
                            Some events may include physical items such as race packets, medals, or promotional materials.
                            The delivery of these items depends on the specific event and organizing body:
                        </p>

                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle mt-1"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading">Event-Specific Arrangements</h6>
                                    <p class="mb-0">
                                        Physical item distribution is managed by individual event organizers. Details will be
                                        provided in your event confirmation email and on the specific event page.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Collection Methods</h5>
                        <ul class="text-muted">
                            <li><strong>Event Day Collection:</strong> Most items are distributed at the event venue on race day</li>
                            <li><strong>Pre-Event Pickup:</strong> Some events offer advance collection at designated locations</li>
                            <li><strong>Post-Event Collection:</strong> Medals and awards may be available after event completion</li>
                            <li><strong>Postal Delivery:</strong> Available for select events (additional charges may apply)</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Delivery Timeframes</h3>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item Type</th>
                                        <th>Delivery Method</th>
                                        <th>Timeframe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Registration Confirmation</td>
                                        <td>Email</td>
                                        <td>Immediate</td>
                                    </tr>
                                    <tr>
                                        <td>Event Instructions</td>
                                        <td>Email/Platform</td>
                                        <td>24-48 hours before event</td>
                                    </tr>
                                    <tr>
                                        <td>Race Results</td>
                                        <td>Platform/Email</td>
                                        <td>Within 24 hours of event</td>
                                    </tr>
                                    <tr>
                                        <td>Digital Certificates</td>
                                        <td>Platform Download</td>
                                        <td>Within 48 hours of results</td>
                                    </tr>
                                    <tr>
                                        <td>Physical Race Packets</td>
                                        <td>Event Venue</td>
                                        <td>Event day or pre-arranged pickup</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Delivery Locations</h3>

                        <h5 class="mb-3">Digital Delivery</h5>
                        <ul class="text-muted mb-4">
                            <li>Email address provided during registration</li>
                            <li>Personal dashboard on TheAthleteX platform</li>
                            <li>Mobile app notifications (when available)</li>
                        </ul>

                        <h5 class="mb-3">Physical Item Collection</h5>
                        <ul class="text-muted">
                            <li><strong>Event Venue:</strong> Primary collection point for race day materials</li>
                            <li><strong>Registration Centers:</strong> Pre-event pickup locations (if applicable)</li>
                            <li><strong>Partner Locations:</strong> Authorized collection points for specific events</li>
                            <li><strong>Postal Delivery:</strong> To registered address (for eligible items and events)</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Delivery Charges</h3>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="bg-success text-white rounded p-3">
                                    <h6 class="mb-2"><i class="fas fa-check me-2"></i>Free Delivery</h6>
                                    <ul class="mb-0 small">
                                        <li>All digital communications</li>
                                        <li>Digital certificates</li>
                                        <li>Event day material collection</li>
                                        <li>Pre-arranged pickup locations</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="bg-warning text-dark rounded p-3">
                                    <h6 class="mb-2"><i class="fas fa-dollar-sign me-2"></i>Charges May Apply</h6>
                                    <ul class="mb-0 small">
                                        <li>Postal delivery of physical items</li>
                                        <li>Express or special delivery requests</li>
                                        <li>International shipping (if applicable)</li>
                                        <li>Replacement item delivery</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Delivery Issues</h3>
                        <p class="text-muted mb-3">
                            If you experience any issues with receiving your event materials or digital deliverables:
                        </p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6>Digital Items</h6>
                                <ul class="text-muted">
                                    <li>Check your email spam/junk folder</li>
                                    <li>Verify your email address in your profile</li>
                                    <li>Log into your participant dashboard</li>
                                    <li>Contact our support team if items are missing</li>
                                </ul>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h6>Physical Items</h6>
                                <ul class="text-muted">
                                    <li>Confirm collection details with event organizer</li>
                                    <li>Bring valid ID and registration confirmation</li>
                                    <li>Check event page for updated collection information</li>
                                    <li>Contact event support for assistance</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Special Circumstances</h3>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading">Weather or Emergency Cancellations</h6>
                            <p class="mb-0">
                                In case of event cancellations due to weather, emergencies, or unforeseen circumstances,
                                delivery of physical items may be delayed or modified. We will provide updates through email
                                and platform notifications.
                            </p>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading">International Participants</h6>
                            <p class="mb-0">
                                Digital deliverables are available worldwide. Physical item delivery for international
                                participants is subject to event organizer policies and may involve additional shipping costs.
                            </p>
                        </div>
                    </div>

                    <div class="bg-light rounded p-4">
                        <h3 class="text-primary mb-3">Contact Information</h3>
                        <p class="text-muted mb-3">
                            For delivery-related questions or issues, please contact our support team:
                        </p>
                        <ul class="text-muted mb-0">
                            <li><strong>Email:</strong> support@theathletex.com</li>
                            <li><strong>Response Time:</strong> Within 24 hours during business days</li>
                            <li><strong>Event Day Support:</strong> Available at event venues during competitions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
