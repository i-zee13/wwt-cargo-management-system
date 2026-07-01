@extends('layouts.master')
@section('data-sidebar')
    <form style="width: 100%" id="saveSettingsForm">
        @csrf
        <input type="text" id="operation" name="operation" hidden>
        <input type="text" id="opp_id" name="opp_id" hidden>
        <input type="text" id="opp_name_input" name="opp_name_input" hidden>
        <div class="offcanvas offcanvas-end AccRights offcanvas-width" tabindex="-1" id="access-right"
            aria-labelledby="access-rightLabel">
            <div class="offcanvas-header">
                <h2 class="offcanvas-heading"> Management</h2>
                <button type="button" class="btn-close close_sidebar" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="form-wrap p-0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 form-group designation_form_div" style="display:none">
                            <label for="name" class="form-label">{{__('fields.role_title')}} *</label>
                            <div class="icon-input">
                                <input type="text" name="designation_name"
                                    class="form-control required_designation avoidSpecialChars" id="designation_name"
                                    value="" placeholder="{{__('fields.enter_role_title')}}">
                            </div>

                        </div>
                        <div class="col-lg-12 col-md-12 form-group department_form_div" style="display:none">
                            <label for="name" class="form-label">{{__('fields.enter_department_name')}} *</label>
                            <div class="icon-input">
                                <input type="text" name="department_name"
                                    class="form-control required_department avoidSpecialChars" id="department_name"
                                    value="" placeholder="{{__('fields.enter_department_name')}}">
                            </div>

                        </div>
                        <div class="col-lg-12 col-md-12 form-group doc_form_div" style="display:none">
                            <label for="name" class="form-label">{{__('fields.document_name')}} *</label>
                            <div class="icon-input">
                                <input type="text" name="document_name"
                                    class="form-control required_doc avoidSpecialChars" id="document_name"
                                    value="" placeholder="{{__('fields.enter_document_name')}}">
                            </div> 
                        </div>
                        <div class="col-lg-12 col-md-12   branch_form_div" style="display:none">
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="name" class="form-label">{{__('fields.branch_name')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="branch"
                                            class="form-control required_branch avoidSpecialChars" id="branch"
                                            value="" placeholder="{{__('fields.enter_branch_name')}}">
                                    </div>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="name" class="form-label">{{__('fields.branch_code')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="code"
                                            class="form-control required_branch avoidSpecialChars" id="code"
                                            value="" placeholder="{{__('fields.enter_branch_code')}}">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-lg-12 col-md-12 form-group languages_form_div" style="display:none">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <label for="language_title" class="form-label">{{__('fields.language_title')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="language_title"
                                            class="form-control required_languages avoidSpecialChars" id="language_title"
                                            value="" placeholder="{{__('fields.enter_language_title')}}">
                                    </div>

                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label for="iso_code" class="form-label">{{__('fields.enter_iso_code')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="iso_code"
                                            class="form-control required_languages avoidSpecialChars" id="iso_code"
                                            value="" placeholder="{{__('fields.enter_language_iso_code')}}">
                                    </div>

                                </div>
                                <div class="col-lg-6 col-md-12" style="margin-top: 10px">
                                    <label for="rtl" class="form-label">{{__('fields.rtl')}} *</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control required_languages select_class" id="rtl"
                                                name="rtl" data-name="rtl" style="width: 100%;">
                                                <option value="0">{{__('fields.no')}}</option>
                                                <option value="1">{{__('fields.yes')}}</option>
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
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="offcanvas-footer">
                <button type="button" class="btn btn-primary me-2" id="saveBtn">{{__('fields.save')}}</button>
                <button type="button" class="btn btn btn-light close_sidebar" data-bs-dismiss="offcanvas">{{__('fields.close')}}</button>
            </div>
        </div>
    </form>
@endsection
@section('content')
    <div class="row m-0">
        <div class="tabs-header border-0 pr-0">
            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fetchDesignation" id="pills-001-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-001" type="button" role="tab" aria-controls="pills-001"
                        aria-selected="true">{{__('fields.roles')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fetchDepartments" id="pills-002-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-002" type="button" role="tab" aria-controls="pills-002"
                        aria-selected="false">{{__('fields.departments')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fetchLanguages" id="pills-003-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-003" type="button" role="tab" aria-controls="pills-003"
                        aria-selected="false">{{__('fields.languages')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fetchBranches" id="pills-004-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-004" type="button" role="tab" aria-controls="pills-004"
                        aria-selected="false">{{__('fields.branches')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fetchDocuments" id="pills-005-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-005" type="button" role="tab" aria-controls="pills-005"
                        aria-selected="false">{{__('fields.document_types')}}</button>
                </li>

            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="pills-001" role="tabpanel"
                        aria-labelledby="pills-001-tab" tabindex="0">
                        <div class="card-header">
                            <div class="row">
                                <div class="col mt-auto mb-auto">
                                    <h2 class="heading02">{{__('fields.designations_list')}}</h2>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-add-guest openDataSidebarForAddingDesignation">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                        </svg> {{__('fields.add_new')}}</button>
                                </div>
                            </div>


                        </div>
                        <div style="min-height: 400px" class="loader">
                            <img src="/images/loader.gif" width="30px" height="auto"
                                style="position: absolute; left: 50%; top: 45%;">
                        </div>
                        <div class="card-body body_designations">

                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-004" role="tabpanel" aria-labelledby="pills-004-tab"
                        tabindex="0">
                        <div class="card-header">
                            <div class="row">
                                <div class="col mt-auto mb-auto">
                                    <h2 class="heading02">{{__('fields.branches_list')}}</h2>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-add-guest openDataSidebarForAddingBranch">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                        </svg> {{__('fields.add_new')}}</button>
                                </div>
                            </div>


                        </div>
                        <div style="min-height: 400px" class="loader">
                            <img src="/images/loader.gif" width="30px" height="auto"
                                style="position: absolute; left: 50%; top: 45%;">
                        </div>
                        <div class="card-body body_branch">

                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-005" role="tabpanel" aria-labelledby="pills-005-tab"
                    tabindex="0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col mt-auto mb-auto">
                                <h2 class="heading02">{{__('fields.document_types_list')}}</h2>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-add-guest openDataSidebarForAddingDoc">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                    </svg> {{__('fields.add_new')}}</button>
                            </div>
                        </div>


                    </div>
                    <div style="min-height: 400px" class="loader">
                        <img src="/images/loader.gif" width="30px" height="auto"
                            style="position: absolute; left: 50%; top: 45%;">
                    </div>
                    <div class="card-body body_doc">

                    </div>
                </div>


                    <div class="tab-pane fade" id="pills-002" role="tabpanel" aria-labelledby="pills-002-tab"
                        tabindex="0">
                        <div class="card-header">
                            <div class="row">
                                <div class="col mt-auto mb-auto">
                                    <h2 class="heading02">{{__('fields.departments_list')}}</h2>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-add-guest openDataSidebarForAddingDepartment">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                        </svg> {{__('fields.add_new')}}</button>
                                </div>
                            </div>
                        </div>
                        <div style="min-height: 400px" class="loader">
                            <img src="/images/loader.gif" width="30px" height="auto"
                                style="position: absolute; left: 50%; top: 45%;">
                        </div>
                        <div class="card-body body_departments">


                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-003" role="tabpanel" aria-labelledby="pills-003-tab"
                        tabindex="0">
                        <div class="card-header">
                            <div class="row">
                                <div class="col mt-auto mb-auto">
                                    <h2 class="heading02">{{__('fields.languages_list')}}</h2>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-add-guest openDataSidebarForAddingLanguage">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                        </svg> {{__('fields.add_new')}}</button>
                                </div>
                            </div>
                        </div>
                        <div style="min-height: 400px" class="loader">
                            <img src="/images/loader.gif" width="30px" height="auto"
                                style="position: absolute; left: 50%; top: 45%;">
                        </div>
                        <div class="card-body body_languages">


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script>
        $('.content_head').text('{{__('fields.settings')}}') 
    </script>
@endpush
