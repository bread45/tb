<script src="{{ asset('/front/trainer/js/jquery-3.4.1.js') }}" type="text/javascript"></script>
<script src="{{ asset('/front/trainer/js/popper.min.js') }}"></script>
<script src="{{ asset('/front/trainer/js/bootstrap.js') }}"></script>
<script src="{{ asset('/front/trainer/js/calendar.js') }}"></script>
<script src="{{ asset('/front/trainer/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/front/trainer/js/dataTables.rowReorder.min.js') }}"></script>
<script src="{{ asset('/front/trainer/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('../front/js/pnotify.custom.min.js') }}"></script>
<script src="{{ asset('/front/trainer/js/developer.js') }}"></script>
<script src="{{ asset('/theme/vendors/general/select2/dist/js/select2.full.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>

<script type="text/javascript">

  document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.sidebar .nav-link').forEach(function (element) {

      element.addEventListener('click', function (e) {

        let nextEl = element.nextElementSibling;
        let parentEl = element.parentElement;

        if (nextEl) {
          e.preventDefault();
          let mycollapse = new bootstrap.Collapse(nextEl);

          if (nextEl.classList.contains('show')) {
            mycollapse.hide();
          } else {
            mycollapse.show();
            // find other submenus with class=show
            var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
            // if it exists, then close all of them
            if (opened_submenu) {
              new bootstrap.Collapse(opened_submenu);
            }

          }
        }

      });
    })

  });
    // DOMContentLoaded  end
</script>
<script type="text/javascript">
     $('html, body').animate({
            scrollTop: $('div.alert').offset().top-200
}, 2000);

$(document).on('change', '.headerlocations', function () {
    var id = $(this).val();
    var url = '{{url("trainer/changelocation")}}/'+id;
        $.ajax({
            url: url,
            type: 'GET',
            success: function (result) {
                location.reload();
            }
        });
}); 
@if(!session()->has('location_id'))
    var id = $('.headerlocations').val();
    var url = '{{url("trainer/changelocation")}}/'+id;
        $.ajax({
            url: url,
            type: 'GET',
            success: function (result) {
                 
            }
        });
@endif
</script>

