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

        $('.new_page').click(function () {
            $.get('{{route('admin.ocr.clear')}}', {}, function (data) {
                location.reload();
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ajaxStart(function () {
            // alert('Playing With Ajax');
            $('#loading').addClass('loading');
            $('#loading-content').addClass('loading-content');
        });

        $(document).ajaxStop(function () {
            $('#loading').removeClass('loading');
            $('#loading-content').removeClass('loading-content');
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
    location.replace('http://127.0.0.1:8000/admin/Add/Visit/Companion/' + data);

    // window.open("http://127.0.0.1:8000/admin/Add/Visit/Companion/" + data);

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
                iziToast.success({
                    title: 'Success',
                    message: 'تمت إضافة البيانات بنجاح',
                    position: 'topRight',
                });

                setTimeout(home, 2000);

                function home() {
                    location.replace('http://127.0.0.1:8000/admin/OCR');
                }
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
                console.log(typeof(data) );

                if (data === 'Error While Adding Companion') {
                    iziToast.error({
                        title: 'error',
                        message: 'حدث خطأ اثناء قراءة بيانات الهوية',
                        position: 'topRight'
                    });
                }

                if (data === 'Visit Not Found') {
                    iziToast.error({
                        title: 'error',
                        message: 'لم يتم ايجاد هذة الزيارة ',
                        position: 'topRight'
                    });
                }

                else {
                    iziToast.success({
                        title: 'Success',
                        message: 'تم إضافة المرافق بنجاح',
                        position: 'topRight'
                    });
                    setTimeout(reload, 2000);

                    function reload() {
                        location.reload();
                    }
                }

                console.log(data);
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


                if(data === 'Error While Adding Companion') {
                    iziToast.error({
                        title: 'error',
                        message: 'حدث خطأ اثناء قراءة بيانات الهوية',
                        position: 'topRight'
                    });
                }
                if(data === 'Visit Not Found') {
                    iziToast.error({
                        title: 'error',
                        message: 'لم يتم ايجاد هذة الزيارة ',
                        position: 'topRight'
                    });
                }

                else {
                    iziToast.success({
                        title: 'Success',
                        message: 'تم إضافة المرافقين بنجاح',
                        position: 'topRight'
                    });
                    setTimeout(reloadPage, 2000)

                    function reloadPage() {
                        location.replace("http://127.0.0.1:8000/admin/OCR");
                    }
                }

            });
        });

        $('.search_worker').click(function () {
            name = $('#name').text();
            nat_id = $('#mrz').text();
            $.post('{{route('admin.find.this.worker')}}', {
                name: name,
                nat_id: nat_id,
            }, function (data) {
                if(data === 'Worker Was Not Found') {
                    iziToast.error({
                        title: 'Error',
                        message: 'لم يتم إيجاد هذا بيانات العامل',
                        position: 'topRight',
                    });
                }

                if(data.status == 200 ) {
                    iziToast.error({
                        title: 'success',
                        message: 'هذا العامل موجود بالفعل',
                        position: 'topRight',
                    });
                }


            });
        });


        $('.newscan').click(function () {
            $(this).serialize();
            // cnf = confirm("Add another Person ID?");
            // if (cnf == true) add = 'Y'; else add = 'N';
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
            employee_id = $('#employee').val();
            car_plate_number = $('#plate_number_input').val();
            car_type = @if(isset($car_type))        '{{$car_type}}' @endif

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
                employee_id : employee_id,
                car_plate_number : car_plate_number,


                add: add,
                car_type : car_type,
            }, function (data) {
                if(data === 'Visitor Error') {
                    iziToast.error({
                        title: 'Error',
                        message: 'حدث خطأ أثناء إدخال بيانات الزائر',
                        position: 'topRight',
                    });
                }

                if(data === 'Visit Error') {
                    iziToast.error({
                        title: 'Error',
                        message: 'حدث خطأ أثناء إنشاء بيانات الزيارة',
                        position: 'topRight',
                    });
                }

                if(data == 'Employee Is Not Specified') {
                        iziToast.error({
                        title: 'Error',
                        message: 'يجب أن تختار موظف لهذة الزيارة',
                        position: 'topRight',
                    });
                }

                if(data == 'Car Plate Is Not Specified') {
                        iziToast.error({
                        title: 'Error',
                        message: 'يجب إختيار رقم السيارة',
                        position: 'topRight',
                    });
                }



                // if(data == 'SQL Server Connection Error') {
                //        iziToast.error({
                //        title: 'Error',
                //        message: 'حدث خطأ اثنتاء الاتصال بقاعدة بيانات ال sql server',
                //        position: 'topRight',
                //    });
                //  }

                else {
                    console.log(data);
                    wnd = window.open("http://127.0.0.1:8000/admin/ocr-print/?id=" + data, '_blank');
                    wnd.print();
                    location.reload();
                }
            });
        });

        $('.get_plate').click(function () {
            $.get('{{route('admin.get.last.car.plate')}}', {}, function (data) {
                // console.log(data);
                location.reload();
            });
        });

        $('#car_plate_number').change(function() {
            var value = $('#car_plate_number').val();
            console.log(value);
            $('#plate_number_input').val('');
            $('#plate_number_input').val(value);
        });
    });
</script>