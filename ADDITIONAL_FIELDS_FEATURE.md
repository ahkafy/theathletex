# Additional Registration Fields Feature

## Overview
This feature allows event administrators to define custom/additional form fields when creating or editing events. These fields will automatically appear in the registration form for participants to fill out.

## Implementation Summary

### 1. Database Changes

#### Events Table
- **Migration**: `2025_10_19_092937_add_additional_fields_to_events_table.php`
- **Column**: `additional_fields` (JSON, nullable)
- **Purpose**: Stores configuration for custom form fields

#### Participants Table
- **Migration**: `2025_10_19_093320_add_additional_data_to_participants_table.php`
- **Column**: `additional_data` (JSON, nullable)
- **Purpose**: Stores participant's responses to additional fields

### 2. Models Updated

#### Event Model (`app/Models/Event.php`)
```php
protected $fillable = [
    // ... existing fields
    'additional_fields',
];

protected $casts = [
    'additional_fields' => 'array',
];
```

#### Participant Model (`app/Models/Participant.php`)
```php
protected $fillable = [
    // ... existing fields
    'additional_data',
];

protected $casts = [
    'additional_data' => 'array',
];
```

### 3. Admin Interface

#### Event Create Form (`resources/views/admin/events/create.blade.php`)
- Added "Additional Registration Fields" section
- Dynamic field builder with:
  - Field Label
  - Field Type (text, email, number, tel, date, textarea, dropdown)
  - Options (for dropdown type)
  - Required checkbox
  - Remove button
- "Add Field" button to add more fields
- JavaScript to handle dynamic field addition/removal

#### Event Edit Form (`resources/views/admin/events/edit.blade.php`)
- Same interface as create form
- Pre-populates existing additional fields
- Allows editing or removing fields

#### Field Types Supported
1. **Text** - Single line text input
2. **Email** - Email validation
3. **Number** - Numeric input
4. **Phone (tel)** - Phone number
5. **Date** - Date picker
6. **Textarea** - Multi-line text
7. **Dropdown (select)** - Options dropdown (comma-separated options)

### 4. Controller Updates

#### EventController (`app/Http/Controllers/Admin/EventController.php`)

**store() method:**
- Validates additional_fields input
- Processes and formats field configuration
- Stores as JSON in database

**update() method:**
- Same validation and processing as store
- Updates existing additional fields

**Field Structure Stored:**
```json
[
  {
    "label": "Blood Group",
    "type": "select",
    "required": true,
    "options": ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"]
  },
  {
    "label": "Medical Conditions",
    "type": "textarea",
    "required": false
  }
]
```

### 5. Registration Form

#### View (`resources/views/registration/form.blade.php`)
- Added "Additional Information" section
- Dynamically renders fields based on event configuration
- Renders appropriate input types:
  - Text/email/number/tel/date → `<input>`
  - Textarea → `<textarea>`
  - Select → `<select>` with options
- Shows required asterisk for required fields
- Proper validation error display

#### RegistrationController (`app/Http/Controllers/RegistrationController.php`)

**registerParticipant() method:**
- Dynamically builds validation rules based on event's additional_fields
- Validates field types (email, numeric, date, etc.)
- Enforces required fields
- Stores participant responses in `additional_data` JSON column

**Validation Logic:**
```php
if ($event->additional_fields) {
    foreach ($event->additional_fields as $field) {
        $fieldLabel = $field['label'];
        $fieldRules = [];
        
        if ($field['required'] ?? false) {
            $fieldRules[] = 'required';
        }
        
        // Type-specific validation
        switch ($field['type']) {
            case 'email': $fieldRules[] = 'email'; break;
            case 'number': $fieldRules[] = 'numeric'; break;
            // ... etc
        }
        
        $rules["additional_data.{$fieldLabel}"] = implode('|', $fieldRules);
    }
}
```

## Usage Guide

### For Admins

#### Creating an Event with Additional Fields

1. Go to **Admin Dashboard → Events → Create Event**
2. Fill in standard event details (name, description, venue, etc.)
3. Scroll to **"Additional Registration Fields"** section
4. Click **"Add Field"** button
5. For each field, enter:
   - **Field Label**: Display name (e.g., "Blood Group")
   - **Field Type**: Select appropriate type
   - **Options**: For dropdown type, enter comma-separated values (e.g., "A+,B+,O+,AB+")
   - **Required**: Check if field is mandatory
6. Click **"Add Field"** to add more fields
7. Click trash icon to remove unwanted fields
8. Click **"Create Event"**

#### Editing Additional Fields

1. Go to **Admin Dashboard → Events → Edit Event**
2. Scroll to **"Additional Registration Fields"** section
3. Existing fields will be shown
4. Modify labels, types, or options
5. Add new fields or remove existing ones
6. Click **"Update Event"**

### For Participants

When registering for an event with additional fields:

1. Fill in standard registration form fields
2. Scroll to **"Additional Information"** section
3. Fill in all required additional fields (marked with *)
4. Optional fields can be left blank
5. Submit registration form

## Examples

### Example 1: Running Event with Medical Info
```javascript
Additional Fields:
- Blood Group (Dropdown, Required): A+, A-, B+, B-, O+, O-, AB+, AB-
- Emergency Contact Name (Text, Required)
- Medical Conditions (Textarea, Optional)
- Allergies (Textarea, Optional)
```

### Example 2: Corporate Event with Company Info
```javascript
Additional Fields:
- Company Name (Text, Required)
- Designation (Text, Required)
- Department (Dropdown, Optional): HR, IT, Finance, Marketing, Operations
- Employee ID (Text, Optional)
```

### Example 3: Youth Event with Parent Info
```javascript
Additional Fields:
- Parent/Guardian Name (Text, Required)
- Parent Phone (Phone, Required)
- School Name (Text, Required)
- Class/Grade (Number, Required)
```

## Data Storage

### Event Table
```json
{
  "id": 1,
  "name": "Marathon 2025",
  "additional_fields": [
    {
      "label": "Blood Group",
      "type": "select",
      "required": true,
      "options": ["A+", "B+", "O+", "AB+"]
    }
  ]
}
```

### Participant Table
```json
{
  "id": 1,
  "event_id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "additional_data": {
    "Blood Group": "A+",
    "Emergency Contact Name": "Jane Doe",
    "Medical Conditions": "None"
  }
}
```

## Deployment Steps

### On Production Server

1. **Pull latest code:**
   ```bash
   git pull origin main
   ```

2. **Run migrations:**
   ```bash
   php artisan migrate
   ```
   This will add:
   - `additional_fields` column to `events` table
   - `additional_data` column to `participants` table

3. **Clear caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

4. **Test the feature:**
   - Create a test event with additional fields
   - Try registering with the additional fields
   - Verify data is stored correctly

## Benefits

✅ **Flexibility**: Each event can have unique registration requirements
✅ **No Code Changes**: Admins can add fields without developer intervention
✅ **Validation**: Automatic type-based validation (email format, numeric, etc.)
✅ **Required Fields**: Enforce mandatory data collection
✅ **User-Friendly**: Intuitive interface for both admins and participants
✅ **Structured Data**: JSON storage allows easy querying and reporting
✅ **Backward Compatible**: Existing events without additional fields work normally

## Future Enhancements (Optional)

- File upload field type
- Radio button field type
- Checkbox group field type
- Conditional fields (show/hide based on other field values)
- Field reordering (drag and drop)
- Export additional data in reports
- Search/filter participants by additional data values

---

**Last Updated**: October 19, 2025
**Status**: ✅ Complete and ready for testing
