@extends('layouts.master')
@section('content')
    <form id="newParentModForm" enctype="multipart/form-data">
        <div class="modal fade" id="modal-amount" tabindex="-1" aria-labelledby="modal-amountLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h2 class="modal-title" id="modal-amountLabel">Parent Module</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="row">

                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="parent_module_name" class="form-label">Parent Module Name *</label>
                                <input type="text" class="form-control" id="parent_module_name"
                                    placeholder="Parent Module Name" name="parent_module_name" required>
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="show_in_sidebar" class="form-label">Show In Sidebar *</label>
                                <div class="icon-input">
                                    <div class="form-s2">
                                        <select class="form-control  " id="show_in_sidebar" name="show_in_sidebar"
                                            data-name="show_in_sidebar" style="width: 100%;">
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
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
                            <div class="col-lg-12 col-md-12 form-group">
                                <label for="icon" class="form-label">Icon*</label>
                                <div class="upload-pic"></div>
                                <input type="file" class="dropify" id="icon" name="parent_icon" accept="image/*"
                                    data-default-file=""
                                    data-allowed-file-extensions="tiff jfif bmp gif svg png jpeg svgz jpg webp ico xbm dib pjp apng tif pjpeg avif">
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="module_name" class="form-label">Sub Module Name *</label>
                                <input type="text" class="form-control" id="module_name" placeholder="Sub Module Name"
                                    name="module_name" required>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="route" class="form-label">Route *</label>
                                <input type="text" class="form-control" id="route" placeholder="Sub Module Route"
                                    name="route" required>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="made_up_name" class="form-label">Made Up Name *</label>
                                <input type="text" class="form-control" id="made_up_name"
                                    placeholder="Sub Module Made Up Name" name="made_up_name" required>
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="show_in_sub_menu" class="form-label">Show In Sub Menu *</label>
                                <div class="icon-input">
                                    <div class="form-s2">
                                        <select class="form-control  " id="show_in_sub_menu" name="show_in_sub_menu"
                                            data-name="show_in_sub_menu" style="width: 100%;">
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
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
                            <dissv class="col-lg-6 col-md-4 assignToDesignationParent form-group">
                                    <label class="form-label" style="font-size:11px">Assign To*</label>
                                    <div class="form-s2">
                                        <select class="reports-select multi assign_to_designationParent" id="parent_mod" name="assign_to_designation[]" multiple="multiple" placeholder="select Designation" style="width: 100%">
                                        </select>
                                    </div>
                                </dissv>
                            <div class="col-12">
                                <button class="btn btn-primary w-100" title="Save Parent Module" id="saveParentMod"> Save
                                </button>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </form>
    <form id="newSubModForm" enctype="multipart/form-data">
        <div class="modal fade" id="modal-sub" tabindex="-1" aria-labelledby="modal-subLabel" aria-hidden="true">
            <div class="modal-dialog
     modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h2 class="modal-title" id="modal-subLabel">Sub Module</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="row">




                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="module_name" class="form-label">Sub Module Name *</label>
                                <input type="text" class="form-control" id="module_name"
                                    placeholder="Sub Module Name" name="module_name" required>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="route" class="form-label">Route *</label>
                                <input type="text" class="form-control" id="route" placeholder="Sub Module Route"
                                    name="route" required>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group">
                                <label for="made_up_name" class="form-label">Made Up Name *</label>
                                <input type="text" class="form-control" id="made_up_name"
                                    placeholder="Sub Module Made Up Name" name="made_up_name" required>
                            </div>
                            <div class="col-lg-6 col-md-6 form-group">
                                <label for="show_in_sub_menu" class="form-label">Show In Sub Menu *</label>
                                <div class="icon-input">
                                    <div class="form-s2">
                                        <select class="form-control  " id="show_in_sub_menu" name="show_in_sub_menu"
                                            data-name="show_in_sub_menu" style="width: 100%;">
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>


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
                            <div class="col-lg-6 col-md-4 form-group assignToDesignationParent">
                                    <label class="form-label">Assign To*</label>
                                    <div class="icon-input">
                                    <div class="form-s2">
                                        <select class="reports-select multi assign_to_designationSub" id="child_mod" name="assign_to_designation[]" multiple="multiple" placeholder="select Designation" style="width: 100%">
                                        </select>
                                    </div>
                                    </div>
                                </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100" title="Save Parent Module" id="saveNewSubMod"> Save
                                </button>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row m-0">
        <div class="card mb-0">
            <div class="row">
                <div class="col p-0">
                    <div class="card-header border-0 pr-0">
                        <div class="row">
                            <div class="col-auto pr-0">
                                <h2>Routes List</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-auto mt-auto mb-auto">
                <input type="hidden" id="get-all-designations" value="{{$designations}}">
                    <button class="btn btn-primary btn-primary-sm me-2  new_parent_module">+ Add New </button>
                    <button class="btn btn-primary btn-primary-sm saveParentPriorityList"> Save Parent Priority List
                    </button>
                    <button type="button" style="display: none" class="btn btn-light me-2 showParentModule"
                        data-bs-toggle="modal" data-bs-target="#modal-amount">Show Parent Module</button>
                    <button type="button" style="display: none" class="btn btn-light me-2 showSubModule"
                        data-bs-toggle="modal" data-bs-target="#modal-sub">Show Sub Module</button>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <ul class="cardlist sortable list-group subMenu">
                @foreach ($data as $item)
                    <li class="parent-li addNewSubMob">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col mt-auto mb-auto new_parent_module"
                                        data-text="{{ $item['parent_module'] }}"><button class="btn parentMod "
                                            value="{{ $item['parent_module'] }}"
                                            title="{{ $item['parent_module'] }}">{{ $item['parent_module'] }}</button>
                                    </div>
                                    <div class="col-auto actions-header">
                                        <button class="btn new_sub_module" title="Add New">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                            </svg>
                                        </button>
                                        <button class="btn savePriorityList" title="Save">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-floppy2-fill" viewBox="0 0 16 16">
                                                <path d="M12 2h-2v3h2z" />
                                                <path
                                                    d="M1.5 0A1.5 1.5 0 0 0 0 1.5v13A1.5 1.5 0 0 0 1.5 16h13a1.5 1.5 0 0 0 1.5-1.5V2.914a1.5 1.5 0 0 0-.44-1.06L14.147.439A1.5 1.5 0 0 0 13.086 0zM4 6a1 1 0 0 1-1-1V1h10v4a1 1 0 0 1-1 1zM3 9h10a1 1 0 0 1 1 1v5H2v-5a1 1 0 0 1 1-1" />
                                            </svg>
                                        </button>
                                        <button class="btn btn-card-del deleteParentMod"
                                            parent-module="{{ $item['parent_module'] }}" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path
                                                    d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <ul class="child-list scroll-y sortable list-group subMenu">
                                    @foreach ($item['sub_mods'] as $sub)
                                        <li parent-module-name="{{ $item['parent_module'] }}" item-id={{ $sub['id'] }}
                                            class="editSubNavItem subModItems subPrior new_sub_module">
                                            {{ $sub['sub_module'] ? $sub['sub_module'] : $sub['made_up_name'] }}
                                            <button class="btn btn-card-del deleteSubNavItem"
                                                item-id="{{ $sub['id'] }}" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                            </button>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </li>
                @endforeach

            </ul>

        </div>
    </div>
@endsection

@push('js')
    <script>
        $('.content_head').text('Site Settings')
        $('.first_crumb').text('Site Settings')
        $('.second_crumb').hide()
    </script>
@endpush
