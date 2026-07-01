@extends('layouts.master')
@section('content')
<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Routes <span>List</span></h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
    <a class="btn add_button addNewParentMod"><i class="fa fa-plus"></i> Add New</a>
    <a class="btn add_button saveParentPriorityList" style="margin-right: 15px"><i class="fa fa-plus"></i> Save Parent
                    Priority List</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card"> 
            <div class="body">
                <ul class="sortable" class="list-group subMenu" style="display: block">
                    @foreach ($data as $item)
                    <li
                        style="height: 300px; overflow: auto; margin-bottom: 20px; width: 31%; display: inline-block; margin-left: 10px; float: left; padding-bottom: 10px">
                        <span
                            style="cursor: pointer; background: #fafafa; padding: 0px; margin: 0px; height: 50px; text-align: center; line-height: 50px; border: 1px solid #040725; text-transform: uppercase; font-weight: bold; display: block;"
                            class="parentMod" value="{{ $item['parent_module'] }}">{{ $item['parent_module'] }}</span>
                        <input type="text" module-name="{{ $item['parent_module'] }}" value={{ $item['parent_module'] }}
                            class="form-control parentModEditor" style="display: none">
                        <div class="form-group p-b-10 p-t-10">
                            <ul class="sortable" class="list-group subMenu">
                                @foreach ($item['sub_mods'] as $sub)
                                <li parent-module-name="{{ $item['parent_module'] }}" item-id={{$sub['id']}}
                                    class="list-group-item subModItems editSubNavItem">
                                    <label style="font-size: 12pt" for="dbV1"
                                        class="lab-medium">{{ $sub['sub_module'] ? $sub['sub_module'] : $sub['made_up_name'] }}</label>
                                    <span class="deleteSubNavItem"
                                        style="float: right; color: red; width: 50px;text-align: right;"
                                        item-id="{{ $sub['id'] }}"><i class="fa fa-trash"></i></span>
                                </li>
                                @endforeach
                                <li class="addNewSubMob"
                                    style="text-align: center; background: #040725; color: white; font-weight: bold;padding:11px"
                                    class="list-group-item">
                                    <label parent-module="{{ $item['parent_module'] }}"
                                        style=" cursor: pointer; font-size: 12pt; font-weight: bold; float: left"
                                        for="dbV1" class="lab-medium deleteParentMod">DELETE</label>
                                    <label style="font-size: 10pt; width: 150px;" for="dbV1" class="lab-medium mb-0">ADD
                                        NEW</label>
                                    <label style="cursor: pointer; font-size: 12pt; font-weight: bold; float: right"
                                        for="dbV1" class="lab-medium savePriorityList">SAVE</label>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(".sortable").sortable();
    </script>
@endpush
