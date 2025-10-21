@extends('layouts.template')

@section('content')

<!-- Main Content -->
<main class="container col-md-6 col-lg-5">
  <!-- Open Events Section -->


  <!-- Instruction Message -->
  <div class="text-center mt-5 mb-3 px-3">
    <h4 class="fw-semibold fs-2 ">Register for {{ $event->name }}</h4>
    <p class="fw-bold">Please fill up this form</p>
  </div>

<div class="card w-100 mb-4">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
            Please fix the errors below.
            </div>
        @endif
        <form method="POST" action="{{ route('register.store', $event->id) }}">
            @csrf

            <div class="mb-3">
                <label for="reg_type" class="form-label">Registration Type*</label>
                <select class="form-select @error('reg_type') is-invalid @enderror" id="reg_type" name="reg_type" required>
                    <option selected disabled>Select Registration Type</option>
                    @foreach($event->fees as $fee)
                        <option value="{{ $fee->id }}" {{ old('reg_type') == $fee->fee_type ? 'selected' : '' }}>
                            {{ ucfirst($fee->fee_type) }}
                            @if(isset($fee->fee_amount))
                                - {{ number_format($fee->fee_amount, 2) }} {{ $fee->currency ?? 'BDT' }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('reg_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category*</label>
                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                    <option selected disabled>Select Category</option>
                    @foreach($event->categories as $category)
                        <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="mb-3">
                <label for="name" class="form-label">Name (English)*</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number*</label>
                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" required value="{{ old('phone', $verifiedPhone ?? '') }}" placeholder="Enter your phone number">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address*</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" required value="{{ old('address') }}">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="district" class="form-label">District*</label>
                <select class="form-select @error('district') is-invalid @enderror" id="district" name="district" required>
                    <option selected disabled>Select District</option>
                    @foreach([
                    'Dhaka','Bagerhat','Bandarban','Barisal','Barguna','Bhola','Bogra','Brahmanbaria','Chandpur','Chapai Nawabganj','Chattogram','Chuadanga','Comilla','Cox\'s Bazar','Dinajpur','Faridpur','Feni','Gaibandha','Gazipur','Gopalganj','Habiganj','Jamalpur','Jashore','Jhenaidah','Jhalokati','Joypurhat','Khagrachari','Khulna','Kishoreganj','Kurigram','Kushtia','Lakshmipur','Lalmonirhat','Magura','Madaripur','Manikganj','Meherpur','Munshiganj','Moulvibazar','Mymensingh','Naogaon','Narayanganj','Narsingdi','Narail','Natore','Netrokona','Nilphamari','Noakhali','Pabna','Panchagarh','Patuakhali','Pirojpur','Rajbari','Rajshahi','Rangamati','Rangpur','Satkhira','Sherpur','Shariatpur','Sirajganj','Sunamganj','Sylhet','Tangail','Thakurgaon'
                    ] as $district)
                    <option value="{{ $district }}" {{ old('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                    @endforeach
                </select>
                @error('district')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="thana" class="form-label">Thana*</label>
                <input type="text" class="form-control @error('thana') is-invalid @enderror" id="thana" name="thana" required value="{{ old('thana') }}">
                @error('thana')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="emergency_phone" class="form-label">Emergency Phone*</label>
                <input type="tel" class="form-control @error('emergency_phone') is-invalid @enderror" id="emergency_phone" name="emergency_phone" required value="{{ old('emergency_phone') }}">
                @error('emergency_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Gender<span class="text-danger">*</span></label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="male" value="Male" {{ old('gender', 'Male') == 'Male' ? 'checked' : '' }}>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="female" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }}>
                    <label class="form-check-label" for="female">Female</label>
                </div>
                @error('gender')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth*</label>
                <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob" required value="{{ old('dob') }}">
                @error('dob')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="nationality" class="form-label">Nationality*</label>
                <select class="form-select @error('nationality') is-invalid @enderror" id="nationality" name="nationality" required>
                    <option value="" disabled {{ old('nationality') ? '' : 'selected' }}>Select Nationality</option>
                    @foreach([
                        'Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda','Argentina','Armenia','Australia','Austria','Azerbaijan',
                        'Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria','Burkina Faso','Burundi',
                        'Cabo Verde','Cambodia','Cameroon','Canada','Central African Republic','Chad','Chile','China','Colombia','Comoros','Congo (Republic)','Congo (DRC)','Costa Rica','Côte d\'Ivoire','Croatia','Cuba','Cyprus','Czechia',
                        'Denmark','Djibouti','Dominica','Dominican Republic',
                        'Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Eswatini','Ethiopia',
                        'Fiji','Finland','France',
                        'Gabon','Gambia','Georgia','Germany','Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana',
                        'Haiti','Honduras','Hungary',
                        'Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy',
                        'Jamaica','Japan','Jordan',
                        'Kazakhstan','Kenya','Kiribati','Kuwait','Kyrgyzstan',
                        'Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg',
                        'Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Mauritania','Mauritius','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Morocco','Mozambique','Myanmar',
                        'Namibia','Nauru','Nepal','Netherlands','New Zealand','Nicaragua','Niger','Nigeria','North Korea','North Macedonia','Norway',
                        'Oman',
                        'Pakistan','Palau','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal',
                        'Qatar',
                        'Romania','Russia','Rwanda',
                        'Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Sweden','Switzerland','Syria',
                        'Taiwan','Tajikistan','Tanzania','Thailand','Timor-Leste','Togo','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Tuvalu',
                        'Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan',
                        'Vanuatu','Vatican City','Venezuela','Vietnam',
                        'Yemen','Zambia','Zimbabwe'
                    ] as $country)
                        <option value="{{ $country }}" {{ old('nationality') == $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
                @error('nationality')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($event->additional_fields && count($event->additional_fields) > 0)
                <!-- Dynamic Additional Fields -->
                <div class="border-top pt-3 mt-4 mb-3">
                    <h6 class="fw-semibold text-muted mb-3">Additional Information</h6>
                    @foreach($event->additional_fields as $index => $field)
                        <div class="mb-3">
                            <label for="additional_{{ $index }}" class="form-label">
                                {{ $field['label'] }}
                                @if($field['required'] ?? false)<span class="text-danger">*</span>@endif
                            </label>

                            @if($field['type'] === 'textarea')
                                <textarea
                                    name="additional_data[{{ $field['label'] }}]"
                                    id="additional_{{ $index }}"
                                    class="form-control @error('additional_data.'.$field['label']) is-invalid @enderror"
                                    rows="3"
                                    {{ ($field['required'] ?? false) ? 'required' : '' }}>{{ old('additional_data.'.$field['label']) }}</textarea>

                            @elseif($field['type'] === 'select')
                                <select
                                    name="additional_data[{{ $field['label'] }}]"
                                    id="additional_{{ $index }}"
                                    class="form-select @error('additional_data.'.$field['label']) is-invalid @enderror"
                                    {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                    <option value="">Select {{ $field['label'] }}</option>
                                    @foreach($field['options'] as $option)
                                        <option value="{{ $option }}" {{ old('additional_data.'.$field['label']) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>

                            @else
                                <input
                                    type="{{ $field['type'] }}"
                                    name="additional_data[{{ $field['label'] }}]"
                                    id="additional_{{ $index }}"
                                    class="form-control @error('additional_data.'.$field['label']) is-invalid @enderror"
                                    value="{{ old('additional_data.'.$field['label']) }}"
                                    {{ ($field['required'] ?? false) ? 'required' : '' }}>
                            @endif

                            @error('additional_data.'.$field['label'])
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label text-muted fw-semibold">T-shirt <span class="text-danger">*</span></label>
                <div>
                    <select class="form-select @error('tshirt_size') is-invalid @enderror" id="tshirt_size" name="tshirt_size" required>
                        <option value="" disabled {{ old('tshirt_size') ? '' : 'selected' }}>Select T-shirt Size</option>
                        @foreach([
                            'XS'    => 'XS (Chest-36", Length-25")',
                            'S'     => 'S (Chest-38", Length-26")',
                            'M'     => 'M (Chest-40", Length-27")',
                            'L'     => 'L (Chest-42", Length-28")',
                            'XL'    => 'XL (Chest-44", Length-29")',
                            'XXL'   => 'XXL (Chest-46", Length-30")',
                            '3XL'   => '3XL (Chest-48", Length-31")',
                            '4XL'   => '4XL (Chest-50", Length-32")',
                            '3-4Y'  => '3-4 Year\'s (Chest-26", Length-18")',
                            '5-6Y'  => '5-6 Year\'s (Chest-28", Length-19")',
                            '7-8Y'  => '7-8 Year\'s (Chest-30", Length-20")',
                            '9-10Y' => '9-10 Year\'s (Chest-32", Length-21")',
                            '11-12Y'=> '11-12 Year\'s (Chest-34", Length-22")',
                        ] as $value => $label)
                            <option value="{{ $value }}" {{ old('tshirt_size') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @error('tshirt_size')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input @error('terms_agreed') is-invalid @enderror" type="checkbox" id="agree" name="terms_agreed" {{ old('terms_agreed') ? 'checked' : '' }}>
                    <label class="form-check-label" for="agree">
                   All payments are final. No refunds or transfers allowed. By registering, I confirm that I have read and agreed to our terms, conditions, and return policy of The Athlete X Limited.
                    </label>
                </div>
                @error('terms_agreed')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn w-100" style="background-color: #6f42c1; color: #fff;">Send</button>
        </form>
    </div>
</div>


</main>
@endsection
