@extends('admin.ocr.layout_main')
@section('content')
    <section class="ftco-section">

        <div class="container">
            <div class="row justify-content-right" style="direction:rtl"><a href="indexar.php">عربى</a>|<a>English</a></div>
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5" style="margin-bottom:0px !important">
                    <h2 class="heading-section">Passport Scanner</h2>
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
                                        <h5>Car Plate:</h5><input type="text" class="form-control plate_no"
                                                                  value="<?php echo $plate ?>"/>
                                        <input type="button" value="Last Car plate" class="btn btn-success get_plate"
                                               style="height: 35px; padding: 7px 14px;margin-left: 7%">
                                    </div>
                                    <br/>
                                    <div class="row" style="text-align:center">
                                        <div class="col-md-3">
                                            <div class="form-group" style="text-align:center;float:left;margin-right:20px">
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
                                            <p><span>Name:</span> <a id="name" class="txt"></a></p>
                                        </div>
                                    </div>
                                    <div class="dbox w-25 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>Date Of Birth:</span> <a id="dob" class="txt"></a></p>
                                        </div>
                                    </div>


                                    <div class="dbox w-25 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-id-card"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>ID Number:</span> <a id="mrz" class="txt"></a></p>
                                        </div>
                                    </div>
                                    <div class="dbox w-25 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-intersex"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>Gender:</span> <a id="sex" class="txt"></a></p>
                                        </div>
                                    </div>


                                    <div class="dbox w-25 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-exclamation-triangle"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>Expiry Date:</span> <a id="exdate" class="txt"></a></p>
                                        </div>
                                    </div>
                                    <div class="dbox w-25 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-tasks"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>Job:</span> <a id="job" class="txt"></a></p>
                                        </div>
                                    </div>
                                    <div class="dbox w-25 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-user"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>Marital status:</span> <a id="mstat" class="txt"></a></p>
                                        </div>
                                    </div>

                                    <div class="dbox w-25 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>Issuing Date:</span> <a id="isdate" class="txt"></a></p>
                                        </div>
                                    </div>

                                    <div class="dbox w-25 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><label for="vdate">Visit Date:</label> <input type="text"
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
                                            <p><label for="vtime">Visit Time:</label> <input type="text"
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
                                            <p><span>Country Code:</span> <a id="icc" class="txt"></a></p>
                                        </div>
                                    </div>
                                    <div class="dbox w-25 d-flex align-items-center" style="display:none !important">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-book"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>Religion:</span> <a id="relg" class="txt"></a></p>
                                        </div>
                                    </div>
                                    <div class="dbox w-25 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-address-card"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>Address:</span> <a id="address" class="txt"></a></p>
                                        </div>
                                    </div>
                                    <div class="dbox w-100 d-flex align-items-center"
                                         style="margin-bottom:0px !important;height: 50px !important;margin-top:55px;text-align:center;padding-left: 35%;">
                                        <div class="form-group">
                                            <input type="button" value="New Scan" class="btn btn-danger new_page">
                                            <input type="button" value="Scan" class="btn btn-danger scan"
                                                   onclick="connect();">
                                            <input type="button" value="Save Data" class="btn btn-success save">
                                            <input type="button" value="View Visitors" class="btn btn-success view">
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
@endsection