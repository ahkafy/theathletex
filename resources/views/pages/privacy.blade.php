@extends('layouts.app')

@section('title', 'Privacy Policy - TheAthleteX')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Privacy Policy</h1>
                <p class="lead text-muted">Your privacy and data security are our top priorities</p>
                <small class="text-muted">Last updated: {{ date('F d, Y') }}</small>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Introduction</h3>
                        <p class="text-muted">
                            TheAthleteX ("we", "our", or "us") is committed to protecting your privacy and ensuring the security
                            of your personal information. This Privacy Policy explains how we collect, use, and safeguard your
                            information when you use our platform for athletic event registration and participation.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Information We Collect</h3>

                        <h5 class="mb-3">Personal Information</h5>
                        <ul class="text-muted mb-4">
                            <li>Name, email address, and phone number</li>
                            <li>Date of birth and gender for event categorization</li>
                            <li>Emergency contact information</li>
                            <li>Athletic performance data and event results</li>
                            <li>Payment information (processed securely through third-party providers)</li>
                        </ul>

                        <h5 class="mb-3">Technical Information</h5>
                        <ul class="text-muted mb-4">
                            <li>IP address and browser information</li>
                            <li>Device information and operating system</li>
                            <li>Usage patterns and interaction data</li>
                            <li>Cookies and similar tracking technologies</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">How We Use Your Information</h3>
                        <ul class="text-muted">
                            <li><strong>Event Registration:</strong> Processing your participation in athletic events</li>
                            <li><strong>Communication:</strong> Sending event updates, confirmations, and important notifications</li>
                            <li><strong>Results Management:</strong> Recording and displaying your athletic performance</li>
                            <li><strong>Payment Processing:</strong> Handling registration fees and transactions securely</li>
                            <li><strong>Platform Improvement:</strong> Analyzing usage to enhance our services</li>
                            <li><strong>Legal Compliance:</strong> Meeting regulatory requirements and protecting our rights</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Information Sharing</h3>
                        <p class="text-muted mb-3">
                            We do not sell, trade, or otherwise transfer your personal information to outside parties except in the following circumstances:
                        </p>
                        <ul class="text-muted">
                            <li><strong>Event Organizers:</strong> Sharing registration details with authorized event organizers</li>
                            <li><strong>Service Providers:</strong> Working with trusted third-party services for payment processing and platform maintenance</li>
                            <li><strong>Public Results:</strong> Displaying event results and rankings (as consented during registration)</li>
                            <li><strong>Legal Requirements:</strong> Complying with legal obligations or protecting against fraud</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Data Security</h3>
                        <p class="text-muted mb-3">
                            We implement comprehensive security measures to protect your personal information:
                        </p>
                        <ul class="text-muted">
                            <li>SSL encryption for all data transmission</li>
                            <li>Secure server infrastructure with regular security updates</li>
                            <li>Access controls limiting data access to authorized personnel only</li>
                            <li>Regular security audits and vulnerability assessments</li>
                            <li>Secure payment processing through PCI-compliant providers</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Your Rights</h3>
                        <p class="text-muted mb-3">You have the following rights regarding your personal information:</p>
                        <ul class="text-muted">
                            <li><strong>Access:</strong> Request a copy of your personal data</li>
                            <li><strong>Correction:</strong> Update or correct inaccurate information</li>
                            <li><strong>Deletion:</strong> Request deletion of your personal data (subject to legal requirements)</li>
                            <li><strong>Portability:</strong> Receive your data in a portable format</li>
                            <li><strong>Opt-out:</strong> Unsubscribe from non-essential communications</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Cookies and Tracking</h3>
                        <p class="text-muted">
                            We use cookies and similar technologies to enhance your experience on our platform. These help us:
                        </p>
                        <ul class="text-muted">
                            <li>Remember your preferences and settings</li>
                            <li>Analyze platform usage and performance</li>
                            <li>Provide personalized content and recommendations</li>
                            <li>Ensure platform security and prevent fraud</li>
                        </ul>
                        <p class="text-muted">
                            You can control cookie settings through your browser, though some platform features may not function properly if cookies are disabled.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Data Retention</h3>
                        <p class="text-muted">
                            We retain your personal information for as long as necessary to provide our services and comply with legal obligations.
                            Event registration data and results are typically maintained for historical record-keeping and athletic achievement tracking.
                            You may request data deletion, subject to legal and legitimate business requirements.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Children's Privacy</h3>
                        <p class="text-muted">
                            Our platform is designed for users of all ages, including minors participating in youth athletic events.
                            For participants under 18, we require parental consent during registration. We take extra care to protect
                            the privacy of young athletes and comply with applicable child protection regulations.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-primary mb-3">Changes to This Policy</h3>
                        <p class="text-muted">
                            We may update this Privacy Policy periodically to reflect changes in our practices or legal requirements.
                            We will notify users of significant changes through email or platform notifications. Continued use of our
                            services after updates constitutes acceptance of the revised policy.
                        </p>
                    </div>

                    <div class="bg-light rounded p-4">
                        <h3 class="text-primary mb-3">Contact Us</h3>
                        <p class="text-muted mb-3">
                            If you have questions about this Privacy Policy or wish to exercise your rights regarding your personal information, please contact us:
                        </p>
                        <ul class="text-muted mb-0">
                            <li><strong>Email:</strong> privacy@theathletex.net</li>
                            <li><strong>Address:</strong> TheAthleteX Privacy Team, Dhaka, Bangladesh</li>
                            <li><strong>Response Time:</strong> We aim to respond to all privacy inquiries within 48 hours</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
