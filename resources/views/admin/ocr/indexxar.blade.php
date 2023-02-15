<!doctype html>
<html lang="en">
<head>
    <title>تسجيل الزوار</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{asset('css/ocr_styles/style.css')}}">
    @include('admin.ocr.index_style')

</head>
@include('admin.ocr.script')
<?php
if (filesize(storage_path('app/public/' . 'plate.txt')) > 0) {
    $myfile = fopen(storage_path('app/public/' . 'plate.txt'), "r");
    $plate = fread($myfile, filesize(storage_path('app/public/' . 'plate.txt')));
    fclose($myfile);
} else {
    $plate = '';
}
?>


<body>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-right" style="direction:rtl"><a>عربى</a>|<a href="{{route('admin.OCR.index')}}">English</a></div>
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5" style="margin-bottom:0px !important">
                <h2 class="heading-section">تسجيل الزيارات</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-14">
                <div class="wrapper">
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="contact-wrap w-100 p-lg-5 p-4">

                                <div id="form-message-warning" class="mb-4"></div>
                                <div id="form-message-success" class="mb-4">
                                    Data was sent, thank you!
                                </div>
                                <div class="row" style="text-align:center">
                                    <h5>سياره رقم:</h5><input type="text" class="form-control plate_no"
                                                              value="<?php echo $plate ?>"/>
                                    <input type="button" value="أستعاده رقم اللوحه" class="btn btn-success get_plate"
                                           style="height: 35px; padding: 7px 14px;margin-right: 7%">
                                </div>
                                <br/>
                                <div class="row" style="text-align:center">
                                    <div class="col-md-3">
                                        <div class="form-group" style="text-align:center;float:right;margin-left:90px">
                                            <img id="pic" src=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <img id="white_picture" src=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <img id="ocr_head" src=""/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <img id="chip_head" src=""/>
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>
                        <div class="div2">
                            <textarea id="msg" cols="75" rows="20"></textarea>
                        </div>
                        <div class="col-md-12 d-flex align-items-stretch">
                            <div class="info-wrap w-100 p-lg-5 p-4 img">


                                <div class="dbox w-25 d-flex align-items-start">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-user"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>الاسم:</span> <a id="name" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>تاريخ الميلاد:</span> <a id="dob" class="txt"></a></p>
                                    </div>
                                </div>

                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-id-card"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>رقم البطاقه / جواز السفر:</span> <a id="mrz" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-intersex"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>النوع:</span> <a id="sex" class="txt"></a></p>
                                    </div>
                                </div>

                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-exclamation-triangle"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>تاريخ الانتهاء:</span> <a id="exdate" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-tasks"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>المؤهل / الوظيفه:</span> <a id="job" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-user"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>الحاله الاجتماعيه:</span> <a id="mstat" class="txt"></a></p>
                                    </div>
                                </div>

                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>تاريخ الاصدار:</span> <a id="isdate" class="txt"></a></p>
                                    </div>
                                </div>

                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><label for="vdate">تاريخ الزياره:</label> <input type="text"
                                                                                            value="<?php echo date('d-m-Y') ?>"
                                                                                            class="vdate form-control"
                                                                                            id="vdate"
                                                                                            disabled="disabled"/></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><label for="vtime">وقت الزياره:</label> <input type="text"
                                                                                          value="<?php echo date('h:i:s a'); ?>"
                                                                                          class="vtime form-control"
                                                                                          id="vtime"
                                                                                          disabled="disabled"/></p>

                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-flag"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>المنطقه / العنوان:</span> <a id="icc" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center" style="display:none !important">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-book"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>الديانه:</span> <a id="relg" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-25 d-flex align-items-center">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <span class="fa fa-address-card"></span>
                                    </div>
                                    <div class="text pl-3">
                                        <p><span>العنوان:</span> <a id="address" class="txt"></a></p>
                                    </div>
                                </div>
                                <div class="dbox w-100 d-flex align-items-center"
                                     style="margin-bottom:0px !important;height: 50px !important;margin-top:55px;text-align:center;padding-right: 35%;">
                                    <div class="form-group">
                                        <input type="button" value="زائر جديد" class="btn btn-danger new_page">
                                        <input type="button" value="بحث" class="btn btn-danger scan"
                                               onclick="connect();">
                                        <input type="button" value="حفظ البيانات" class="btn btn-success save">
                                        <input type="button" value="سجل الزيارات" class="btn btn-success view">
                                        <div class="submitting"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="images" style="display:none"></div>
<div class="perpic" style="display:none"></div>



@include('admin.ocr.index_footer_scripts')

<style>
    .div2 {
        top: 220px;
        width: 500px;
        position: fixed;
        float: right;
        display: none
    }

    #white_picture {
        width: 100%;
        min-width: 100%;
        min-height: 150px;
        height: 150px;
        border: 1px solid #000;
    }

    #ocr_head {
        width: 100%;
        min-width: 100%;
        min-height: 150px;
        height: 150px;
        border: 1px solid #000;
    }

    #chip_head {
        width: 100%;
        min-width: 100%;
        min-height: 150px;
        height: 150px;
        border: 1px solid #000;
    }

    #pic {
        width: 160px;
        min-width: 160px;
        min-height: 150px;
        height: 150px;
        border: 1px solid #000;
    }

    .text {
        font-size: 18px;
        color: #000
    }

    .dbox {
        float: right
    }

    .vdate, .vtime {
        border: 1px solid #000;
        width: 70%;
        height: 45px;
        border-radius: 20px;
        color: #000 !important
    }

    .vdate:focus, .vtime:focus {
        border: 1px solid #000 !important;;
        color: #000 !important
    }

    h5 {
        margin-right: 2%;
        color: #FFF
    }

    .plate_no {
        width: 150px;
        height: 35px;
        margin-right: 1%;
        border: 1px solid #FFF;
        border-radius: 5px;
        background-color: transparent !important;
        color: #FFF;
        font-size: 18px
    }
</style>

</body>
</html>

