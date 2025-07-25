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
        <form method="POST" action="{{ route('register.store', $event->slug) }}">
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
                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" required readonly value="{{ old('phone', $verifiedPhone ?? '') }}">
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
                <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" required value="{{ old('nationality') }}">
                @error('nationality')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label text-muted fw-semibold">T-shirt <span class="text-danger">*</span></label>
                <div class="d-flex flex-wrap gap-3">
                    @foreach(['4XL','3XL','2XL','XL','L','M','S','XS'] as $size)
                        <div class="form-check">
                            <input class="form-check-input @error('tshirt_size') is-invalid @enderror" type="radio" name="tshirt_size" id="size{{ strtolower($size) }}" value="{{ $size }}" {{ old('tshirt_size') == $size ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="size{{ strtolower($size) }}">
                            @switch($size)
                                @case('4XL') 4XL (C-50, L-31.5) @break
                                @case('3XL') 3XL (C-48, L-31) @break
                                @case('2XL') 2XL (C-46, L-30.5) @break
                                @case('XL') XL (C-44, L-30) @break
                                @case('L') L (C-42, L-29) @break
                                @case('M') M (C-40, L-28) @break
                                @case('S') S (C-38, L-27) @break
                                @case('XS') XS (C-36, L-26) @break
                            @endswitch
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('tshirt_size')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Dynamic Fields -->
            @if($event->dynamic_fields_config && count($event->dynamic_fields_config) > 0)
                <div class="mb-4">
                    <h6 class="text-muted">Additional Information</h6>
                    @foreach($event->dynamic_fields_config as $index => $field)
                        <div class="mb-3">
                            <label for="dynamic_field_{{ $index }}" class="form-label">
                                {{ $field['label'] }}
                                @if($field['required']) <span class="text-danger">*</span> @endif
                            </label>
                            @if(isset($field['info']) && !empty($field['info']))
                                <div class="form-text text-muted small mb-2">{{ $field['info'] }}</div>
                            @endif

                            @switch($field['type'])
                                @case('text')
                                    <input type="text"
                                           class="form-control @error('dynamic_fields.'.$index) is-invalid @enderror"
                                           id="dynamic_field_{{ $index }}"
                                           name="dynamic_fields[{{ $index }}]"
                                           value="{{ old('dynamic_fields.'.$index) }}"
                                           {{ $field['required'] ? 'required' : '' }}>
                                    @break

                                @case('email')
                                    <input type="email"
                                           class="form-control @error('dynamic_fields.'.$index) is-invalid @enderror"
                                           id="dynamic_field_{{ $index }}"
                                           name="dynamic_fields[{{ $index }}]"
                                           value="{{ old('dynamic_fields.'.$index) }}"
                                           {{ $field['required'] ? 'required' : '' }}>
                                    @break

                                @case('number')
                                    <input type="number"
                                           class="form-control @error('dynamic_fields.'.$index) is-invalid @enderror"
                                           id="dynamic_field_{{ $index }}"
                                           name="dynamic_fields[{{ $index }}]"
                                           value="{{ old('dynamic_fields.'.$index) }}"
                                           {{ $field['required'] ? 'required' : '' }}>
                                    @break

                                @case('date')
                                    <input type="date"
                                           class="form-control @error('dynamic_fields.'.$index) is-invalid @enderror"
                                           id="dynamic_field_{{ $index }}"
                                           name="dynamic_fields[{{ $index }}]"
                                           value="{{ old('dynamic_fields.'.$index) }}"
                                           {{ $field['required'] ? 'required' : '' }}>
                                    @break

                                @case('select')
                                    <select class="form-select @error('dynamic_fields.'.$index) is-invalid @enderror"
                                            id="dynamic_field_{{ $index }}"
                                            name="dynamic_fields[{{ $index }}]"
                                            {{ $field['required'] ? 'required' : '' }}>
                                        <option value="">Select {{ $field['label'] }}</option>
                                        @if(isset($field['options']) && is_array($field['options']))
                                            @foreach($field['options'] as $option)
                                                <option value="{{ $option }}" {{ old('dynamic_fields.'.$index) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @break

                                @case('textarea')
                                    <textarea class="form-control @error('dynamic_fields.'.$index) is-invalid @enderror"
                                              id="dynamic_field_{{ $index }}"
                                              name="dynamic_fields[{{ $index }}]"
                                              rows="3"
                                              {{ $field['required'] ? 'required' : '' }}>{{ old('dynamic_fields.'.$index) }}</textarea>
                                    @break
                            @endswitch

                            @error('dynamic_fields.'.$index)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
            @endif

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
