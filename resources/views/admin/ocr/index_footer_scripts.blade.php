<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/main.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript" src="js/jquery.redirect.js"></script>
<script>
    $().ready(function () {
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
        $('.view').click(function () {

            $.redirect("view.php", {}, "POST", null, null, true);

        });

        $('.new_page').click(function () {
            $.post("clear.php", {}, function (data) {
                location.reload();
            });
        });
        $('.save').prop('disabled', true);

        $('.save').click(function () {
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
            $.post("save.php", {
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
                wnd = window.open("http://localhost/form/print.php?id=" + data, '_blank');
                wnd.print();
                location.reload();

            });
        });

        $('.get_plate').click(function () {
            $.post("max.php", {}, function (data) {
                location.reload();
            });
        });

    });
</script>
