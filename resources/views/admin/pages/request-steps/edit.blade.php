@extends('admin.layouts.default')
@section('title', 'Question Edit')
@section('content')
<link href="{{ asset('/theme/vendors/general/tagify.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Question Step</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('question.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('question.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf

                <input type="hidden" name="request_step_id" value="{{$requestSteps->id}}" />

                <div class="kt-portlet__body">

                    <!-- <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Title:</label>
                            <input type="text" class="form-control" placeholder="Please enter your title" name="title" value="{{ isset($requestSteps) ? $requestSteps->title : old('title') }}">
                            <span class="form-text text-muted">Please enter your title</span>
                            @if ($errors->has('title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div> -->

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Question:</label>
                            <input type="text" class="form-control" placeholder="Please enter your question" name="question" value="{{ isset($requestSteps) ? $requestSteps->question : old('question') }}">
                            <span class="form-text text-muted">Please enter your question</span>
                            @if ($errors->has('question'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('question') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Answer Type</label>
                        <div class="kt-radio-inline">
                            <label class="kt-radio">
                                <input class="answer_type_click" type="radio" name="answer_choice" value="radio" {{(isset($requestSteps) && $requestSteps->answer_choice == "radio") ? 'checked' : ''}}> Radio
                                <span></span>
                            </label>
                            <label class="kt-radio">
                                <input class="answer_type_click" type="radio" name="answer_choice" value="checkbox" {{(isset($requestSteps) && $requestSteps->answer_choice == "checkbox") ? 'checked' : ''}}> Checkbox
                                <span></span>
                            </label>
                            <label class="kt-radio">
                                <input class="answer_type_click" type="radio" name="answer_choice" value="input" {{(isset($requestSteps) && $requestSteps->answer_choice == "input") ? 'checked' : ''}}> Input
                                <span></span>
                            </label>
                        </div>
                        <span class="form-text text-muted">Please select which type of you answer disaply.</span>
                        @if ($errors->has('answer_choice'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('answer_choice') }}</div>
                        @endif
                    </div>

                    <div class="form-group row is_answer_type">
                        <div class="col-lg-12">
                            <div style="margin-bottom: 10px">
                                <label>Answers: </label>
                                <button type="button" class="btn btn-primary btn-sm add_new_ans_field" style="margin-left: 5px" title="Add"><i class="fa fa-plus"></i> Add New</button>
                            </div>
                            <span class="form-text text-muted">Add multiple answers of your question.</span>
                            <div class="answer_clone" style="display: none;">
                                <div class="added_answer_clone" style="display: flex; margin-bottom: 10px">
                                    <input type="text" class="form-control" placeholder="Please enter your answer" name="answers[]">
                                    <button type="button" class="btn btn-danger btn-sm btn-icon remove_clone" style="margin-left: 5px" title="Remove"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>

                            <div class="append_clone">
                                @if(old('answers'))
                                @foreach(array_filter(old('answers')) as $answer)
                                <div class="added_answer_clone" style="display: flex; margin-bottom: 10px">
                                    <input type="text" class="form-control" placeholder="Please enter your answer" name="answers[]" value="{{$answer}}">
                                    <button type="button" class="btn btn-danger btn-sm btn-icon remove_clone" style="margin-left: 5px" title="Remove"><i class="fa fa-minus"></i></button>
                                </div>
                                @endforeach
                                @else
                                @if(isset($requestSteps->answers))
                                @foreach($requestSteps->answers as $answer)
                                <div class="added_answer_clone" style="display: flex; margin-bottom: 10px">
                                    <input type="text" class="form-control" placeholder="Please enter your answer" name="answers[]" value="{{$answer}}">
                                    <button type="button" class="btn btn-danger btn-sm btn-icon remove_clone" style="margin-left: 5px" title="Remove"><i class="fa fa-minus"></i></button>
                                </div>
                                @endforeach
                                @endif
                                @endif
                            </div>

                            @if ($errors->has('answers'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('answers') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Select Services</label>
                            <select class="form-control kt-select2" id="kt_select2_3" name="services[]" multiple="multiple">
                                @foreach($categories as $categorie)
                                <optgroup label="{{$categorie->name}}">
                                    @foreach($categorie->services as $service)
                                    <option value="{{$service->id}}" {{(isset($requestSteps) && isset($requestSteps->services) && in_array($service->id, $requestSteps->services)) ? 'selected' : ''}}>{{$service->name}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" name="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-secondary reset">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="contactus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ajax_content"></div>
        </div>
    </div>
</div>
<!-- end:: Content -->
@stop

@section('pagescript')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.min.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
    $(document).ready(function() {
        $("#kt_select2_3").select2({
            placeholder: "Select a Services"
        });

        $(".reset").click(function() {
            $('.kt-form').find("input[type=text], textarea").val("");
            
            @php
            if (isset($requestSteps) && $requestSteps->answer_choice == "input") {
                @endphp
                $(".is_answer_type").hide();
                @php
            }else{
                @endphp
                $(".is_answer_type").show();
                @php
            }
            @endphp

            setTimeout(function() {
                $("#kt_select2_3").trigger('change');
            });
        });
        var input = document.querySelector('input[name=tags]');

        // init Tagify script on the above inputs
        new Tagify(input)

        @php
        if (isset($requestSteps) && $requestSteps->answer_choice == "input") {
            @endphp
            $(".is_answer_type").hide();
            @php
        }
        @endphp

        $(document).on("click", ".answer_type_click", function() {
            if ($(this).val() == 'input') {
                $(".is_answer_type").hide();
            } else {
                $(".is_answer_type").show();
            }
        });

        $(document).on("click", ".add_new_ans_field", function() {
            $(".answer_clone div").clone().appendTo(".append_clone");
        });
        $(document).on("click", ".remove_clone", function() {
            $(this).closest('.added_answer_clone').remove();
        });
    });
</script>

@endsection