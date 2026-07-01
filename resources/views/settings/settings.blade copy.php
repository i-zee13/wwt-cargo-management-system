@extends('layouts.master')
@section('data-sidebar')
    <div id="product-cl-sec">
        <a id="pl-close" class="close-btn-pl"></a>
        <div class="pro-header-text">New <span id="opp_name"></span></div>
        <div style="min-height: 400px" id="dataSidebarLoader" style="display: none">
            <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
        </div>
        <div class="pc-cartlist">
            <div class="overflow-plist">
                <div class="plist-content">
                    <div class="_left-filter">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <form style="display: flex;" id="saveSettingsForm">
                                        @csrf
                                        <input type="text" id="operation" name="operation" hidden>
                                        <input type="text" id="opp_id" name="opp_id" hidden>
                                        <input type="text" id="opp_name_input" name="opp_name_input" hidden>

                                        <div id="floating-label" class="card p-20 top_border mb-3 designation_form_div"
                                            style="width: 100%; display:none">
                                            <h2 class="_head03">Designation <span>Details</span></h2>
                                            <div class="form-wrap p-0 font13">
                                                <div class="row">
                                                    <div class="col-md-12 pt-5">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10">Designation Name*</label>
                                                            <input type="text" name="designation_name"
                                                                class="form-control required_designation">
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <div id="floating-label" class="card p-20 top_border mb-3 department_form_div"
                                            style="width: 100%; display:none">
                                            <h2 class="_head03">Department <span>Details</span></h2>
                                            <div class="form-wrap p-0">
                                                <div class="row">
                                                    <div class="col-md-12 pt-5">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10">Department Name*</label>
                                                            <input type="text" name="department_name"
                                                                class="form-control required_department">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="_cl-bottom">
            <button type="button" class="btn btn-primary mr-2" id="saveBtn">Save</button>
            <button id="pl-close" type="button" class="btn btn-cancel mr-2" id="cancelBtn">Cancel</button>
        </div>
    </div>
@endsection


@section('content')
    <div class="row mt-2 mb-3">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <h2 class="_head01">Settings <span>Details</span></h2>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card _Dispatch">
                <div class="row m-0">
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="nav flex-column nav-pills CB-account-tab" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                             
                            <a class="nav-link active " id="v-pills-01-tab" data-toggle="pill" href="#v-pills-01" role="tab"
                                aria-controls="v-pills-01" aria-selected="false">Designations</a>
                            <a class="nav-link" id="v-pills-02-tab" data-toggle="pill" href="#v-pills-02" role="tab"
                                aria-controls="v-pills-02" aria-selected="false">Departments </a>
                           
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-12 ml-800">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-01" role="tabpanel"
                                aria-labelledby="v-pills-01-tab">
                                <div class="col-md-12 PT-20 mb-0">
                                    <h2 class="_head04">Designations
                                        <a class="btn add_button openDataSidebarForAddingDesignation"
                                            style="right:0px; top:-7px;"><i class="fa fa-plus"></i> New Designation</a>
                                    </h2>
                                </div>
                                <div style="min-height: 400px" class="loader">
                                    <img src="/images/loader.gif" width="30px" height="auto"
                                        style="position: absolute; left: 40%; top: 45%;">
                                </div>
                                <div class="col-md-12 productRate-table m-0 body_designations mt-20 mb-30">

                                </div>
                            </div>

                            <div class="tab-pane fade " id="v-pills-02" role="tabpanel" aria-labelledby="v-pills-02-tab">
                                <div class="col-md-12 mb-0" style="padding-top:20px !important">
                                    <h2 class="_head04">Departments
                                        <a class="btn add_button openDataSidebarForAddingDepartment"
                                            style="right:0px; top:-7px;"><i class="fa fa-plus"></i> New Department</a>
                                    </h2>
                                </div>
                                <div style="min-height: 400px" class="loader">
                                    <img src="/images/loader.gif" width="30px" height="auto"
                                        style="position: absolute; left: 40%; top: 45%;">
                                </div>
                                <div class="col-md-12 productRate-table m-0 body_departments mt-20 mb-30">

                                </div>
                            </div>
                        


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
