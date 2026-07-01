@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mt-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col-auto pr-0">

                        </div>
                        <div class="col mt-auto mb-auto pl-0 pr-0">
                            <h2>{{ @$action == 'edit' ? 'Edit' : 'Create' }} Access Rights</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form style="width: 100%" id="saveRightsForm">
                                @csrf
                                <div class="row">
                                    <input type="text" id="operation" hidden value="{{ $action }}">
                                    <div class="col-lg-6 col-md-12 form-group">
                                        <label for="employee_id" class="form-label">Employee *</label>
                                        <div class="icon-input">
                                            <div class="form-s2">
                                                <select class="form-control emp_countries required select_class"
                                                    @if ($action == 'edit') disabled @endif id="employee_id"
                                                    name="employee_id" data-name="Employee" style="width: 100%;">
                                                    <option value="0">Select Employee</option>

                                                    @foreach ($employees as $emp)
                                                        <option value="{{ $emp->id }}"
                                                            @if ($action == 'edit') selected @endif>
                                                            {{ $emp->username }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                                                    stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                    </div>
                                    <div class="col-lg-6 col-md-12 form-group">

                                    </div>

                                    <div class="col-lg-12 col-md-12 form-group">
                                        <div class="card-header mb-3">
                                            <h2>Rights List</h2>
                                        </div>
                                        {{-- Rights List --}}
                                        <div class="custom-control custom-checkbox mr-sm-2 selectall">
                                            <input type="checkbox" name="rights[]" class="custom-control-input all_rights"
                                                value="" id="br-001">
                                            <label class="custom-control-label" for="br-001">Select All</label>
                                        </div>
                                        <div class="row">
                                        @foreach ($controllers as $count => $heading)

                                                <div
                                                    class="col-lg-12 col-md-12 form-group {{ $count == 0 ? 'position-relative' : '' }}">
                                                    <h3 class="{{ $count == 0 ? 'mt-10' : '' }}">
                                                        <div>
                                                            <div class="custom-control custom-checkbox mr-sm-2">
                                                                <input type="checkbox" name="rights[]"
                                                                    heading="{{ $heading['heading'] }}"
                                                                    class="custom-control-input access_rights_headings"
                                                                    value="" id="select-all-{{ $count }}">

                                                                <label for="select-all-{{ $count }}"
                                                                    style="font-size: 16px"
                                                                    class="form-label">{{ $heading['heading'] }}</label>
                                                            </div>
                                                        </div>
                                                    </h3>
                                                </div>
                                                @foreach ($heading['sub_mod'] as $key => $rights)
                                                    <?php $isChecked = collect($accessed_rights)->contains('controller_right', $rights['controller']); ?>
                                                    <div class="col-lg-3 col-md-12 form-group">
                                                        <div class="custom-control custom-checkbox mr-sm-2">
                                                            <input type="checkbox" value="{{ $rights['controller'] }}"
                                                                name="rights[]" heading="{{ $heading['heading'] }}"
                                                                class="custom-control-input access_rights_emp"
                                                                id="{{ $rights['made_up_name'] . $key }}"
                                                                {{ $isChecked ? 'checked' : '' }}>

                                                            <label for="{{ $rights['made_up_name'] . $key }}"
                                                                class="form-label"> {{ $rights['made_up_name'] }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                        @endforeach  
                                        </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100" id="saveRights" title="Save">Save</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('.content_head').text('Rights Management')
        $('.first_crumb').text('Organization')
        $('.second_crumb').text('Access Rights')
    </script>
@endpush
