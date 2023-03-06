<Script>
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


        $('.edit').click(function () {
            vid = $(this).attr('id');

            $.redirect("edit.php", {vid: vid}, "POST", null, null, true);
        });

        $('.find').click(function () {
            v2date = $('.v2date').val();
            v3date = $('.v3date').val();
            $.redirect("view.php", {v2date: v2date, v3date: v3date}, "POST", null, null, true);
        });


        $('.delete').click(function () {
            id = $(this).attr('id');
            $.post("delete.php", {id: id}, function (data) {
                location.reload();
            });
        });
        $('.newscan').click(function () {
            $.redirect("index.php", {}, "POST", null, null, true);
        });
        $('.clr').click(function () {
            $.redirect("view.php", {}, "GET", null, null, true);
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


