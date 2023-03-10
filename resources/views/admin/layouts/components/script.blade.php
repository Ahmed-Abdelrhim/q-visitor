<!-- General JS Scripts -->
<script src="{{ asset('assets/modules/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/modules/popper.js/dist/popper.min.js') }}"></script>
<script src="{{ asset('assets/modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/modules/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('assets/modules/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/js/stisla.js') }}"></script>

<!-- JS Libraies -->
<script src="{{ asset('assets/modules/izitoast/dist/js/iziToast.min.js') }}"></script>
@yield('scripts')

<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    @if(session('success'))
    iziToast.success({
        title: 'Success',
        message: '{{ session('success') }}',
        position: 'topRight'
    });
    @endif

    @if(session('error'))
    iziToast.error({
        title: 'Error',
        message: '{{ session('error') }}',
        position: 'topRight'
    });
    @endif
</script>


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

    flatpickr("#v2date", {
        enableTime: true,
        noCalendar: false,
        // dateFormat: "d-m-Y H:i",
        dateFormat: "Y-m-d H:i",
        defaultDate: "today"
    });
    flatpickr("#v3date", {
        enableTime: true,
        noCalendar: false,
        // dateFormat: "d-m-Y H:i",
        dateFormat: "Y-m-d H:i",
        defaultDate: "today"
    });

    flatpickr("#vdate", {
        enableTime: false,
        noCalendar: false,
        // dateFormat: "d-m-Y H:i",
        dateFormat: "Y-m-d",
        defaultDate: "today"
    });




    //     element = document.getElementById('pp').parentNode;
    // console.log(element);


    // element = document.getElementsByClassName('fa-eye').item(0);
    // console.log(element);
</script>
