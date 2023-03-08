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


        // Just For Playing


        $('.save').prop('disabled', true);
        $('.finish').prop('disabled', true);

        $('.another_companion').prop('disabled', true);
        $('.last_companion').prop('disabled', true);


        $('.save').click(function () {
            $(this).serialize();
            // cnf = confirm("Add another Person ID?");
            // if (cnf == true) add = 'Y'; else add = 'N';
            add = 'Y';
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
            id = $('.save').attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{route('admin.ocr.save')}}', {
                id: id,
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
                // Save
                console.log(data);
                wnd = window.open("http://127.0.0.1:8000/admin/ocr-print/?id=" + data, '_blank');
                wnd.print();
                // location.reload();

                {{--window.location.href = '{{  }}';--}}
                {{--location.replace("{{ route('admin.add.companion.to.visit' , }}"+ data + "{{   ) }}" );--}}

                window.open("http://127.0.0.1:8000/admin/Add/Visit/Companion/" + data);

            });
        });


        $('.finish').click(function () {
            $(this).serialize();
            add = 'N';
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
            id = $('.save').attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{route('admin.ocr.save')}}', {
                id: id,
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
                add: add,
            }, function (data) {
                // Finish
                console.log(data);
                wnd = window.open("http://127.0.0.1:8000/admin/ocr-print/?id=" + data, '_blank');
                wnd.print();
                location.reload();
            });
        });


        $('.another_companion').click(function () {
            $(this).serialize();
            add = 'N';
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
            id = $('.another_companion').attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{route('admin.add.another.companion')}}', {
                id: id,
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
                add: add,
            }, function (data) {
                // Another Companion
                console.log(data);
                iziToast.success({
                    title: 'Success',
                    message: 'تم إضافة المرافق بنجاح',
                    position: 'topRight'
                });
                // location.reload();
            });
        });


        $('.last_companion').click(function () {
            $(this).serialize();
            add = 'N';
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
            id = $('.last_companion').attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{route('admin.add.last.companion')}}', {
                id: id,
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
                add: add,
            }, function (data) {
                // Last Companion
                console.log(data);
                iziToast.success({
                    title: 'Success',
                    message: 'تم إضافة المرافقين بنجاح',
                    position: 'topRight'
                });
                setTimeout(reloadPage,2000)
                function reloadPage() {
                    location.replace("http://127.0.0.1:8000/admin/OCR");
                }
            });
        });


        $('.newscan').click(function () {
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{route('admin.new.scan.post')}}', {
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
                wnd = window.open("http://127.0.0.1:8000/admin/ocr-print/?id=" + data, '_blank');
                wnd.print();
                location.reload();
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


{{--$.ajax({--}}
{{--type: 'GET',--}}
{{--enctype: 'multipart/form-data',--}}
{{--url: "{{route('admin.ocr.save')}}",--}}
{{--// dataType: 'JSON',--}}
{{--// processData: false,--}}
{{--// contentType: false,--}}
{{--// cache: false,--}}

{{--async: false,--}}
{{--data: {--}}
{{--// '__token': __token,--}}
{{--// 'name': JSON.stringify(name),--}}
{{--// 'name': name,--}}
{{--// 'gender': gender,--}}
{{--// 'address': full_address,--}}
{{--// 'nat_id': nat_id,--}}
{{--// 'checkin_date': checkin_date,--}}
{{--// 'checkin_time': checkin_time,--}}
{{--// 'images': JSON.stringify(images),--}}
{{--'images': images,--}}
{{--// 'perpic': perpic,--}}
{{--// 'exdate': exdate,--}}
{{--// 'plate_no': plate_no,--}}
{{--// 'add': add,--}}
{{--},--}}

{{--success: function (data) {--}}
{{--console.log(data);--}}
{{--// wnd = window.open("http://localhost/visitorpass/ocr-print?id=" + data, '_blank');--}}
{{--// wnd.print();--}}
{{--// location.reload();--}}
{{--}--}}
{{--, error: function (reject) {--}}
{{--console.log(reject);--}}
{{--// var response = $.parseJSON(reject.responseText);--}}
{{--// $.each(response.errors , function(key , value) {--}}
{{--//     $('#'+key+'_error').text(value[0]);--}}
{{--// })--}}
{{--},--}}

{{--});--}}


{{--name = 'Ahmed Abdelrhim Ahmed';--}}
{{--gender = 'M';--}}
{{--address = 'Egypt , cairo';--}}
{{--nat_id = '29911100104271';--}}
{{--address2 = 'Cairo';--}}
{{--checkin_date = {{\Illuminate\Support\Carbon::now()->toDateString()}};--}}
{{--checkin_time = {{time()}};--}}
{{--plate_no = 'ل ق أ 284 ';--}}
{{--full_address = 'Elmassara ,Helwan , Cairo ,Egypt';--}}
{{--images = ';,' + '{{asset('storage/45/my-pic-2-(2)') }}' ;--}}
{{--prepic = '{{asset('storage/44/my-pic-2')}}';--}}

{{--// $.post("save.php", {--}}
{{--// $.post( '{ {storage_path('app/public' . '/' .'save.php')}}', {--}}
