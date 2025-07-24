@extends('layouts.app')

@section('title', 'Return Policy - TheAthleteX')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Return & Refund Policy</h1>
                <p class="lead text-muted">Understanding our policies on event registrations and payments</p>
                <small class="text-muted">Last updated: {{ date('F d, Y') }}</small>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="alert alert-warning mb-5">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="alert-heading">Important Notice</h5>
                                <p class="mb-0">
                                    <strong>All event registration fees are non-refundable and non-transferable.</strong>
                                    By completing your registration and payment, you acknowledge and agree to this policy.
                                    Please read the full terms below for detailed information.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Policy Overview</h3>
                        <p class="text-muted">
                            TheAthleteX operates in the athletic event industry where advance planning, resource allocation,
                            and commitment are essential for successful event execution. Our no-refund policy ensures that
                            event organizers can make necessary arrangements and commitments based on confirmed registrations.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">No Refund Policy</h3>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="border border-danger rounded p-4 h-100">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                                    </div>
                                    <h5 class="text-center text-danger">No Refunds</h5>
                                    <ul class="text-muted">
                                        <li>Registration fees are non-refundable</li>
                                        <li>No exceptions for personal circumstances</li>
                                        <li>No refunds for weather cancellations</li>
                                        <li>No refunds for participant no-shows</li>
                                        <li>No partial refunds for any reason</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="border border-danger rounded p-4 h-100">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-ban fa-2x text-danger"></i>
                                    </div>
                                    <h5 class="text-center text-danger">No Returns</h5>
                                    <ul class="text-muted">
                                        <li>Event registrations cannot be returned</li>
                                        <li>No transfer to other events</li>
                                        <li>No transfer to other participants</li>
                                        <li>No exchange for different categories</li>
                                        <li>No deferral to future events</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Why This Policy Exists</h3>
                        <p class="text-muted mb-3">
                            Our no-refund policy is in place for several important reasons:
                        </p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar-check text-primary fa-lg mt-1"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6>Event Planning</h6>
                                        <p class="text-muted mb-0">
                                            Organizers make commitments to venues, vendors, and services based on registration numbers.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-truck text-primary fa-lg mt-1"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6>Resource Allocation</h6>
                                        <p class="text-muted mb-0">
                                            Materials, medals, certificates, and refreshments are ordered in advance.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-users text-primary fa-lg mt-1"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6>Staff & Volunteers</h6>
                                        <p class="text-muted mb-0">
                                            Event staffing and volunteer coordination depend on confirmed participant numbers.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-shield-alt text-primary fa-lg mt-1"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6>Insurance & Safety</h6>
                                        <p class="text-muted mb-0">
                                            Event insurance and safety measures are calculated based on registered participants.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Exceptional Circumstances</h3>

                        <div class="alert alert-info">
                            <h6 class="alert-heading">Event Organizer Cancellation</h6>
                            <p>
                                If an event is officially cancelled by the organizer before the event date due to circumstances
                                beyond their control (such as natural disasters, government restrictions, or safety concerns),
                                participants will be notified immediately.
                            </p>
                            <p class="mb-0">
                                <strong>Please note:</strong> Even in cases of organizer cancellation, refunds are not automatically
                                guaranteed. Each situation will be evaluated individually, and any refund decisions are at the
                                sole discretion of the event organizer.
                            </p>
                        </div>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading">Weather-Related Cancellations</h6>
                            <p class="mb-0">
                                Athletic events may proceed in various weather conditions. Cancellations due to weather are rare
                                and typically only occur for extreme safety concerns. Weather-related cancellations do not qualify
                                for automatic refunds.
                            </p>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">What Happens to Your Registration</h3>

                        <p class="text-muted mb-3">
                            Even if you cannot attend an event, your registration still provides value:
                        </p>

                        <ul class="text-muted">
                            <li><strong>Event Support:</strong> Your registration fee supports the organization and execution of the event</li>
                            <li><strong>Community Investment:</strong> Contributes to the growth of the athletic community in Bangladesh</li>
                            <li><strong>Platform Maintenance:</strong> Helps maintain and improve the TheAthleteX platform</li>
                            <li><strong>Future Events:</strong> Enables organizers to plan better future events</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Before You Register</h3>

                        <p class="text-muted mb-3">
                            To avoid disappointment, please carefully consider the following before completing your registration:
                        </p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="bg-light rounded p-3">
                                    <h6><i class="fas fa-calendar me-2 text-primary"></i>Check Your Schedule</h6>
                                    <p class="text-muted mb-0 small">
                                        Ensure you're available on the event date and can commit to participation.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="bg-light rounded p-3">
                                    <h6><i class="fas fa-map-marker-alt me-2 text-primary"></i>Verify Location</h6>
                                    <p class="text-muted mb-0 small">
                                        Confirm the event venue and ensure you can travel to the location.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="bg-light rounded p-3">
                                    <h6><i class="fas fa-heartbeat me-2 text-primary"></i>Assess Fitness Level</h6>
                                    <p class="text-muted mb-0 small">
                                        Ensure you're physically prepared for the event category you're selecting.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="bg-light rounded p-3">
                                    <h6><i class="fas fa-file-alt me-2 text-primary"></i>Read Event Details</h6>
                                    <p class="text-muted mb-0 small">
                                        Thoroughly review all event information, requirements, and policies.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Payment Processing</h3>
                        <p class="text-muted">
                            All payments are processed securely through our trusted payment partners. Once payment is completed:
                        </p>
                        <ul class="text-muted">
                            <li>Your registration is immediately confirmed</li>
                            <li>Payment confirmation is sent to your email</li>
                            <li>The transaction is final and cannot be reversed</li>
                            <li>Your spot in the event is secured</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Technical Issues</h3>
                        <p class="text-muted">
                            If you experience technical issues during the registration or payment process:
                        </p>
                        <ul class="text-muted">
                            <li>Contact our support team immediately</li>
                            <li>Provide detailed information about the issue</li>
                            <li>Keep records of any error messages or payment confirmations</li>
                            <li>We will investigate and resolve technical problems promptly</li>
                        </ul>

                        <div class="alert alert-success">
                            <p class="mb-0">
                                <strong>Technical Support:</strong> For genuine technical issues that prevent successful registration
                                or cause duplicate payments, we will work with you to resolve the problem appropriately.
                            </p>
                        </div>
                    </div>

                    <div class="bg-light rounded p-4">
                        <h3 class="text-primary mb-3">Contact Information</h3>
                        <p class="text-muted mb-3">
                            If you have questions about this return policy or need clarification before registering:
                        </p>
                        <ul class="text-muted mb-3">
                            <li><strong>Email:</strong> support@theathletex.com</li>
                            <li><strong>Subject Line:</strong> "Return Policy Inquiry"</li>
                            <li><strong>Response Time:</strong> Within 24 hours during business days</li>
                        </ul>

                        <p class="text-muted mb-0">
                            <strong>Important:</strong> By completing your registration and payment, you acknowledge that you have
                            read, understood, and agree to this Return & Refund Policy.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
