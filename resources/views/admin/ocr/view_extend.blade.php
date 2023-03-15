<script>
    $(document).ready(function () {
        $('#view_visitor').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>


<style>
    .find, .newscan, .clr {
        height: 40px;
        width: 150px;
        margin-top: 23px;
        margin-left: 12px;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#truck').click(function() {
            $.redirect("{{route('admin.new.scan','T')}}", {}, "GET", null, null, true);
        });
        $('#car').click(function() {
            $.redirect("{{route('admin.new.scan','C')}}", {}, "GET", null, null, true);
        });

        $('#person').click(function() {
            $.redirect("{{route('admin.new.scan','P')}}", {}, "GET", null, null, true);
        });




        $('.edit').click(function () {
            vid = $(this).attr('id');

            $.redirect("edit.php", {vid: vid}, "POST", null, null, true);
        });

        $('.find').click(function () {
            v2date = $('.v2date').val();
            v3date = $('.v3date').val();
            // $.redirect("view.php", {v2date: v2date, v3date: v3date}, "POST", null, null, true);
            $.redirect("{{route('admin.ocr.search.visitors')}}", {
                v2date: v2date,
                v3date: v3date
            }, "GET", null, null, true);
        });


        {{--$('.delete').click(function () {--}}
        {{--    id = $(this).attr('id');--}}
        {{--    // $.post("delete.php", {id: id}, function (data) {--}}
        {{--    $.post("{{route('')}}", {id: id}, function (data) {--}}
        {{--        location.reload();--}}
        {{--    });--}}
        {{--});--}}


        {{--$('.newscan').click(function () {--}}
        {{--    $.ajax({--}}
        {{--        type: 'POST',--}}
        {{--        url : '{{route('admin.new.scan',car_type)}}',--}}
        {{--    });--}}

        {{--    // $.redirect("index.php", {}, "POST", null, null, true);--}}
        {{--    $.redirect("{{route('admin.new.scan')}}", {--}}
        {{--        // 'cs'--}}
        {{--    }, "GET", null, null, true);--}}
        {{--});--}}





        $('.clr').click(function () {
            // $.redirect("view.php", {}, "GET", null, null, true);
            $.redirect("{{route('admin.reload.ocr.view')}}", {}, "GET", null, null, true);
        });
        flatpickr("#v2date", {
            enableTime: false,
            noCalendar: false,
            dateFormat: "d-m-Y",
            defaultDate: "today"
        });
        flatpickr("#v3date", {
            enableTime: false,
            noCalendar: false,
            dateFormat: "d-m-Y",
            defaultDate: "today"
        });
    });
</script>

<script src="{{ asset('assets/modules/izitoast/dist/js/iziToast.min.js') }}"></script>
<script>
    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type','info') }}"
    switch (type) {
        case 'info':
            iziToast.info({
                title: 'info',
                message: '{{ session('message') }}',
                position: 'topRight'
            });
            break;

        case 'success':
            iziToast.success({
                title: 'Success',
                message: '{{ session('message') }}',
                position: 'topRight'
            });
            break;

        case 'warning':
            iziToast.warning({
                title: 'warning',
                message: '{{ session('message') }}',
                position: 'topRight'
            });
            break;

        case 'error':
            iziToast.error({
                title: 'error',
                message: '{{ session('message') }}',
                position: 'topRight'
            });
            break;
    }
    @endif
</script>




