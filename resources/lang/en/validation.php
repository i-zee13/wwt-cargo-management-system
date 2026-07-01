<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'custom' => [
        'first_name.required' => 'The first name is required.',
        'last_name.required' => 'The last name is required.',
        'email.required' => 'The email is required.',
        'email.unique' => 'The email has already been taken.',
        'email.same' => 'The email and confirm email must match.',
        'confirm_email.required' => 'The confirm email is required.',
        'phone.required' => 'The phone number is required.',
        'country.required' => 'The country is required.',
        'country.exists' => 'The selected country is invalid.',
        'state.required' => 'The state is required.',
        'state.exists' => 'The selected state is invalid.',
        'address.required' => 'The address is required.',
        'password.required' => 'The password is required.',
        'password.same' => 'The password and confirm password must match.',
        'confirm_password.required' => 'The confirm password is required.',
        'document_type.required' => 'The document type is required.',
        'document_type.exists' => 'The selected document type is invalid.',
        'document_number.required' => 'The document number is required.',
        'branch.required' => 'The branch is required.',
        'branch.exists' => 'The selected branch is invalid.',
        'postal_code.required' => 'The postal code is required.',
    ],
    'required' => 'The :attribute field is required.',
    'email' => [
        'unique' => 'The email has already been taken.',
        'same' => 'The email and confirm email must match.',
    ],
    'password' => [
        'same' => 'The password and confirm password must match.',
    ],
    'document_type' => [
        'exists' => 'The selected document type is invalid.',
    ],
    'branch' => [
        'exists' => 'The selected branch is invalid.',
    ],
    'country' => [
        'exists' => 'The selected country is invalid.',
    ],
    'state' => [
        'exists' => 'The selected state is invalid.',
    ],
    'attributes' => [
        'first_name' => 'first name',
        'last_name' => 'last name',
        'email' => 'email',
        'confirm_email' => 'confirm email',
        'phone' => 'phone number',
        'country' => 'country',
        'state' => 'state',
        'address' => 'address',
        'password' => 'password',
        'confirm_password' => 'confirm password',
        'document_type' => 'document type',
        'document_number' => 'document number',
        'branch' => 'branch',
        'postal_code' => 'postal code',
    ],
    'accepted' => 'The :attribute must be accepted.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'numeric' => 'The :attribute must not be greater than :max.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'string' => 'The :attribute must not be greater than :max characters.',
        'array' => 'The :attribute must not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

     

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
   
    'name' => 'Name',
    'email' => 'Email',
    'password' => 'Password',
    'confirm_password' => 'Confirm Password',
    'username' => 'Username',
    'enter_username' => 'Enter Username',
    'forget_password' => 'Forget Password?',
    'confirm_email' => 'Confirm Email',
    'address' => 'Address',
    'postal_code' => 'Postal Code',
    'enter_postal_code' => 'Enter Postal Code',
    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'enter_first_name' => 'Enter Your First Name',
    'enter_last_name' => 'Enter Your Last Name',
    'enter_confirm_email' => 'Enter Your Email to Confirm',
    'enter_email' => 'Enter Your Email',
    'phone_number' => 'Phone Number / WhatsApp',
    'select_state' => 'Select Your State',
    'select_country' => 'Select Your Country',
    'street_address' => 'Street Address',
    'enter_your_address' => 'Enter Your Address',
    
    'enter_password' => 'Enter Your Password',
    'branch' => 'Branch',
    'select_branch' => 'Select a Branch',
    'document_type' => 'Document Type',
    'select_document_type' => 'Select Document Type',
    'document_number' => 'Document Number',
    'enter_document_number' => 'Enter Your Document Number',
    'already_have_account' => 'Already have an account? Login',
    'no_account' => "Don't have an account? Sign Up",
    'create_account' => 'Create Your Account',
    'login' => 'Login',
    //
    'sign_up' => 'Sign Up',
    'signing_up' => 'Signing Up',
    'enter_your_phone' => 'Enter Your Phone',
    'spanish' => 'Spanish',
    'customers_today' => 'Customers Today',
    'package_today' => 'Package Created Today',
    'package_retire' => 'Packages to Retire',
    'customers_today' => 'Customer Today',
    'create_package' => 'Create Package',
    'create_origin' => 'Create Origin',
    'create_rate' => 'Create Freight Rates',
    'print_label' => 'Print Label',
    'update_package_status' => 'Update Package Status',
    'quick_actions' => 'Quick Actions',
    'rights_management' => 'Rights Management',
    'manage_rights_management' => 'Manage Access Rights',
    'select_all' => 'Select All',
    'search_access_rights' => 'Search Access Rights Here...',
    'save' => 'Save',
    'close' => 'Close',
    'access_rights_list' => 'Access Rights List',
    'sno' => 'SNO',
    'role' => 'Role',
    'total_rights' => 'Total Rights',  
    'actions' => 'Actions',  
    'Manage Locations' => 'Manage Locations',  

    'dashboard' => 'Dashboard',
    'control_center' => 'Control Center',
    'designation_access_rights' => 'Designation Access Rights',
    'geolocations' => 'GeoLocations', 
    'organization' => 'Organization',
    'employees_list' => 'Employees List',
    'print_package_label' => 'Print Package Label',
    'email_content_settings' => 'Email Content Settings',
    'site_settings' => 'Site Settings',
    'settings' => 'Settings',
  

];
