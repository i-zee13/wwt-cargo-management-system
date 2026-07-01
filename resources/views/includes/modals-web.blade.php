<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content top-borderRed p-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete <span></span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <strong>
                            <div class="modal-custom-text">Are you sure you want to delete?
                            </div>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn w-btn btn-primary confirm_delete confirmStatusChangeEmp">{{__('fields.yes')}}</button>
                <button type="submit" class="btn w-btn btn-cancel btn-outline-danger cancel_delete_modal cancel_delete_modal_activities"
                    data-dismiss="modal" aria-label="Close">{{__('fields.no')}}</button>
            </div>
        </div>
    </div>
    <button hidden data-toggle="modal" data-target="#deleteModal" id="hidden_btn_to_open_modal"></button>
</div>
<div class="modal fade" id="deleteSubModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content top-borderRed p-0">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete <span></span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <strong>
                            <div class="modal-text-custom" style="font-weight: bold;">Are you sure you want to delete?
                            </div>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn w-btn btn-primary confirm_delete_sub_record">Yes</button>
                <button type="submit" class="btn w-btn btn-cancel btn-outline-danger cancel_delete_modal_sub_record"
                    data-dismiss="modal" aria-label="Close">No</button>
            </div>
        </div>
    </div>
    <button hidden data-toggle="modal" data-target="#deleteSubModal" id="hidden_btn_to_open_sub_delte_modal"></button>
</div>
{{-- Thank You Modal --}}
{{-- <div class="modal fade" id="thankyou-confirm" tabindex="-1" role="dialog" aria-labelledby="thankyou-confirmTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content top_border">
            <div class="modal-header">
                <h3 class="modal-title" id="thankyou-confirmTitle">Thank You For Filling Out Your Information</h3>
                <button type="button" class="close fade close-thankyou-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body confir_pay">
                <div class="col-12">
                    <div class="check_mark">
                        <div class="sa-icon sa-success animate">
                            <span class="sa-line sa-tip animateSuccessTip"></span>
                            <span class="sa-line sa-long animateSuccessLong"></span>
                            <div class="sa-placeholder"></div>
                            <div class="sa-fix"></div>
                        </div>
                    </div>
                </div>
                <div class="signup-option">
                    <h3>Data Successfully Submitted, We Review Your Details and Contact Back Soon.</h3>

                </div>
            </div>
        </div>
    </div>
    <button hidden data-toggle="modal" data-target="#thankyou-confirm" id="hidden_btn_to_open_content"></button>
</div> --}}
<div class="modal fade" id="showContentModel" tabindex="-1" role="dialog" aria-labelledby="showContentModel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content top-borderRed p-0">
            <div class="modal-header">
                <h5 class="modal-title modal-custom-title" id="showContentModel">Inquiry<span></span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <strong>
                            <div class="modal-custom-text">
                            </div>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">

                <button type="button" class="btn w-btn btn-cancel btn-outline-danger close_modal" data-dismiss="modal"
                    aria-label="Close">Close</button>
            </div>
        </div>
    </div>
    <button hidden data-toggle="modal" data-target="#showContentModel" id="hidden_btn_to_open_content"></button>
</div>

<div class="modal fade" id="removeActivityData" tabindex="-1" role="dialog" aria-labelledby="removeActivityData"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content top-borderRed p-0">
            <div class="modal-header">
                <h5 class="modal-title modal-custom-title" id="removeActivityData">Activity<span></span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <strong>
                            <div class="modal-custom-text">
                                Are you sure you want to change the type of this activity?
                                <span class="text-danger">Warning! On changing the activity type, all previously added options will be removed.</span> 
                            </div>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn w-btn btn-primary confirm_remove_prev_options">Yes</button>
                <button type="button" class="btn w-btn btn-cancel btn-outline-danger close_modal" data-dismiss="modal"
                    aria-label="Close">Close</button>
            </div>
        </div>
    </div>
    <button hidden data-toggle="modal" data-target="#removeActivityData"
        id="hidden_btn_to_open_activity_erase_model"></button>
</div>
<div class="modal fade" id="infoModuleModel" tabindex="-1" role="dialog" aria-labelledby="infoModuleModel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content top-borderRed p-0">
            <div class="modal-header">
                <h5 class="modal-title" id="modelInfo">Confirmation <span></span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <strong>
                            <div class="modal-custom-text">Are you sure you want to change module?
                            </div>
                            <span class="text-danger span-danger">Lesson Content will marked as</span>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn w-btn btn-primary confirm_change_module confirmStatusChangeEmp">{{__('fields.yes')}}</button>
                <button type="submit" class="btn w-btn btn-cancel btn-outline-danger  "
                    data-dismiss="modal" aria-label="Close">{{__('fields.no')}}</button>
            </div>
        </div>
    </div>
    <button hidden data-toggle="modal" data-target="#infoModuleModel" id="module_change_model"></button>
</div>