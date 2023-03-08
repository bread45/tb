<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };
</script>

<!-- end::Global Config -->

<!--begin:: Global Mandatory Vendors -->
<script src="{{ asset('/theme/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/popper.js/dist/umd/popper.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/js-cookie/src/js.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/moment/min/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tooltip.js/dist/umd/tooltip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/sticky-js/dist/sticky.min.js') }}" type="text/javascript"></script>
<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
<script src="{{ asset('/theme/vendors/general/jquery-form/dist/jquery.form.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/block-ui/jquery.blockUI.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/custom/js/vendors/bootstrap-datepicker.init.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/bootstrap-select/dist/js/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/select2/dist/js/select2.full.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/jquery-validation/dist/jquery.validate.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/jquery-validation/dist/additional-methods.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/custom/js/vendors/jquery-validation.init.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/toastr/build/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/jquery.repeater/src/lib.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/jquery.repeater/src/jquery.input.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/jquery.repeater/src/repeater.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/dompurify/dist/purify.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/sweetalert2/dist/sweetalert2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/custom/js/vendors/sweetalert2.init.js') }}" type="text/javascript"></script>
<script src="{{ asset('../front/js/calendar.js') }}"></script>
{{-- <script src="{{ asset('/theme/vendors/general/ckeditor-classic.bundle.js') }}" type="text/javascript"></script> --}}
{{-- <script src="{{ asset('/theme/vendors/general/ckeditor-classic.js') }}" type="text/javascript"></script> --}}

<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{ asset('/theme/js/demo1/scripts.bundle.js') }}" type="text/javascript"></script>
<!--end::Global Theme Bundle -->

<!--begin::Page Vendors(used by this page) -->
<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<!--<script src="{{ asset('/theme/js/demo1/pages/dashboard.js') }}" type="text/javascript"></script>-->
<!--end::Page Scripts -->
<script src="//cdn.ckeditor.com/4.13.0/full/ckeditor.js"></script>
<script>
    $(".ckeditor").each(function() {
        CKEDITOR.replace($(this).attr("name"));
    });
    toastr.options = {
        "positionClass": "toast-bottom-right",
        "progressBar": true,
        "closeButton": true,
    };


    function imagePreviewLoadUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
                $('document').find('.imagePreviewLoadUrl')
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }


    $('.amount').on('keyup', function() {
        var valid = /^\d{0,10}(\.\d{0,2})?$/.test(this.value.replace('.', '')),
            val = this.value.replace('.', '');
        if (!valid) {
            this.value = val.substring(0, val.length - 1);
        }
    });

    function lettersOnly(evt) {
        evt = (evt) ? evt : event;
        var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode :
            ((evt.which) ? evt.which : 0));
        if (charCode > 31 && (charCode < 65 || charCode > 90) &&
            (charCode < 97 || charCode > 122)) {
            return false;
        }
        return true;
    }

    function textAlphanumericWithSpace(evt) {
        var alphaExp = /^[a-zA-Z0-9 ]*$/;
        /* Minimum length of value in inpute more than one inpute. */
        if (evt.currentTarget.value.length == 0) {
            return true;
        }
        /* Check validation match or not */
        if (evt.currentTarget.value.match(alphaExp)) {
            return true;
        } else {
            evt.currentTarget.value = evt.currentTarget.value.substring(0, evt.currentTarget.value.length - 1);
            return false;
        }
    }
</script>