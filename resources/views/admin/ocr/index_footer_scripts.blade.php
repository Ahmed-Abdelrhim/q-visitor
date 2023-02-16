{{--<script src="js/jquery.min.js"></script>--}}
<script src="{{asset('js/ocr_scripts/jquery.min.js')}}"></script>

{{--<script src="js/popper.js"></script>--}}
<script src="{{asset('js/ocr_scripts/popper.js')}}"></script>

{{--<script src="js/bootstrap.min.js"></script>--}}
<script src="{{asset('js/ocr_scripts/bootstrap.min.js')}}"></script>

{{--<script src="js/jquery.validate.min.js"></script>--}}
<script src="{{asset('js/ocr_scripts/jquery.validate.min.js')}}"></script>

{{--<script src="js/main.js"></script>--}}
<script src="{{asset('js/ocr_scripts/main.js')}}"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{--<script type="text/javascript" src="js/jquery.redirect.js"></script>--}}
<script type="text/javascript" src="{{asset('js/ocr_scripts/jquery.redirect.js')}}"></script>

<script>
    // import * as url from "url";

    $(document).ready(function () {
        /*flatpickr("#v2time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "13:45"
        });
        flatpickr("#v3date", {
            enableTime: false,
            noCalendar: false,
            dateFormat: "d-m-Y",
            defaultDate: "today"
        });*/
        //$('#vdate').datepicker();
        // $('.view').click(function () {
        //
        //     $.redirect("view.php", {}, "POST", null, null, true);
        //
        // });

        $('.new_page').click(function () {
            $.get('{{route('admin.ocr.clear')}}', {}, function (data) {
                location.reload();
            });
        });
        $('.save').prop('disabled', true);


        $('.save').click(function () {
            $(this).serialize();
            cnf = confirm("Add another Person ID?");
            if (cnf == true) add = 'Y'; else add = 'N';
            $(this).prop('disabled', true);
            obj = $(this);
            $(this).attr('value', 'Saving...');
            name = $('#name').text();
            gender = $('#sex').text();
            address = $('#icc').text();
            nat_id = $('#mrz').text();
            address2 = $('#address').text();
            full_address = address2 + ' ' + address;
            checkin_date = $('#vdate').val();
            checkin_time = $('#vtime').val();
            images = $('.images').text();
            perpic = $('.perpic').text();
            exdate = $('#exdate').text();
            plate_no = $('.plate_no').val();

            __token = $('input[name="_token"]').val();

            // var requestData = JSON.stringify([
            //     name,gender,nat_id,address2,full_address,checkin_date,checkin_time,images,perpic,exdate,plate_no,
            // ]);


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // if ($('#images')[0].files.length > 0) {
            //     for (var i = 0; i < $('#images')[0].files.length; i++)
            //         $('#images')[0].append('file[]', $('#images')[0].files[i]);
            // }

            {{--$.ajax({--}}
            {{--    type: 'GET',--}}
            {{--    enctype: 'multipart/form-data',--}}
            {{--    url: "{{route('admin.ocr.save')}}",--}}
            {{--    // dataType: 'JSON',--}}
            {{--    // processData: false,--}}
            {{--    // contentType: false,--}}
            {{--    // cache: false,--}}

            {{--    async: false,--}}
            {{--    data: {--}}
            {{--        // '__token': __token,--}}
            {{--        // 'name': JSON.stringify(name),--}}
            {{--        // 'name': name,--}}
            {{--        // 'gender': gender,--}}
            {{--        // 'address': full_address,--}}
            {{--        // 'nat_id': nat_id,--}}
            {{--        // 'checkin_date': checkin_date,--}}
            {{--        // 'checkin_time': checkin_time,--}}
            {{--        // 'images': JSON.stringify(images),--}}
            {{--        'images': images,--}}
            {{--        // 'perpic': perpic,--}}
            {{--        // 'exdate': exdate,--}}
            {{--        // 'plate_no': plate_no,--}}
            {{--        // 'add': add,--}}
            {{--    },--}}

            {{--    success: function (data) {--}}
            {{--        console.log(data);--}}
            {{--        // wnd = window.open("http://localhost/visitorpass/ocr-print?id=" + data, '_blank');--}}
            {{--        // wnd.print();--}}
            {{--        // location.reload();--}}
            {{--    }--}}
            {{--    , error: function (reject) {--}}
            {{--        console.log(reject);--}}
            {{--        // var response = $.parseJSON(reject.responseText);--}}
            {{--        // $.each(response.errors , function(key , value) {--}}
            {{--        //     $('#'+key+'_error').text(value[0]);--}}
            {{--        // })--}}
            {{--    },--}}

            {{--});--}}


            // $.post("save.php", {
            // $.post( '{ {storage_path('app/public' . '/' .'save.php')}}', {
            $.post('{{route('admin.ocr.save')}}', {
                __token: __token,
                name: name,
                gender: gender,
                address: full_address,
                nat_id: nat_id,
                checkin_date: checkin_date,
                checkin_time: checkin_time,
                images: images,
                perpic: perpic,
                exdate: exdate,
                plate_no: plate_no,
                add: add
            }, function (data) {
                console.log(data);
                // wnd = window.open("http://localhost/visitorpass/ocr-print?id=" + data, '_blank');
                // wnd.print();
                // location.reload();

            });


        });

        $('.get_plate').click(function () {
            $.get('{{route('admin.get.last.car.plate')}}', {}, function (data) {
                // console.log(data);
                location.reload();
            });

        });


    });
</script>
