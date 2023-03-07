<script type="text/javascript">
    function subtractYears(date, years) {
        date.setFullYear(date.getFullYear() - years);
        return date;
    }

    var websocket = null;

    function connect() {
        //var msg = document.getElementById("msg");
        $('.scan').attr('value', 'Scanning...');
        $('.scan').prop('disabled', true);
        $('.scan').css('background-color', '#CCC');


        var hd = document.getElementById("hd");
        var tp = '';
        var imagestr = '';
        document.getElementById("name").innerHTML = "";
        document.getElementById("dob").innerHTML = "";
        document.getElementById("icc").innerHTML = "";
        document.getElementById("mrz").innerHTML = "";
        document.getElementById("sex").innerHTML = "";
        try {
            var readyState = new Array("on connection", "Connection established",
                "Closing connection", "Close connection");
            var host = "ws://127.0.0.1:90/echo";
            // var host = "ws://127.0.0.1:8000/echo";

            websocket = new WebSocket(host);
            console.log('New websocket', websocket);

            websocket.onopen = function () {
                //msg.innerHTML = "Socket state: " + readyState[websocket.readyState];
                hd.innerHTML = "Device " + readyState[websocket.readyState];
                console.log('Open state', websocket.readyState);
            }

            websocket.onmessage = function (event) {
                var str = event.data;//json = JSON.parse(str);
                json = JSON.parse(str.replace(/"([\w\s]+)":/g, function (m) {
                    return m.replace(/\s+/g, '_');
                }));
                var strsub = str;
                if (strsub != "") {
                    var strwhite = "";
                    var strhead = "";
                    var strChipHead = "";
                    var strpic = "";
                    str = strsub.replace(/\*/g, "\r\n");
                    if (str.indexOf("text information") != -1) {

                        msg.innerHTML = "Receive information:" + str;
                        document.getElementById("white_picture").style.display = "none";
                        document.getElementById("ocr_head").style.display = "none";
                        document.getElementById("chip_head").style.display = "none";
                    } else if (str.indexOf("DESC") != -1) {
                        msg.innerHTML = "Receive notification:" + str;
                    } else {
                        msg.innerHTML += "Receive information:" + str;

                        var seek = str.split("data:image/jpeg;base64,");
                        var len = seek.length;
                        for (var i = 1; i < len; i++) {
                            var strType = seek[i][0] + seek[i][1];
                            seek[i] = seek[i].substr(2);
                            if (strType == "01")
                                strwhite = "data:image/jpeg;base64," + seek[i];
                            else if (strType == "08")
                                strhead = "data:image/jpeg;base64," + seek[i];
                            else if (strType == "16")
                                strChipHead = "data:image/jpeg;base64," + seek[i];
                            //alert(strType+seek[i]);
                            //alert(json.Param.White);
                            //strwhite ="data:image/jpeg;base64," + strType+seek[i];

                            //strhead ="data:image/jpeg;base64," + '/9'+seek[i];

                            //strChipHead ="data:image/jpeg;base64," + '/9'+seek[i];


                            if ("White" in json.Param) {
                                strwhite = json.Param.White;
                                strwhite = strwhite.slice(0, -2);
                                strwhite = strwhite.replace('"', '');
                            }
                            if ("UV" in json.Param) {
                                strhead = json.Param.UV;
                                strhead = strhead.slice(0, -2);
                                strhead = strhead.replace('"', '');
                            }
                            if ("IR" in json.Param) {
                                strChipHead = json.Param.IR;
                                strChipHead = strChipHead.slice(0, -2);
                                strChipHead = strChipHead.replace('"', '');
                                tp = 'Y';
                                strpic = "data:image/jpeg;base64," + strType + seek[i];
                                strpic = strpic.slice(0, -2);
                                strpic = strpic.replace('"', '');
                            } else {

                            }


                        }
                        document.getElementById("white_picture").style.display = "inline";
                        document.getElementById("ocr_head").style.display = "inline";
                        document.getElementById("chip_head").style.display = "inline";
                        document.getElementById("pic").style.display = "inline";

                    }

                    document.getElementById("white_picture").src = strwhite;
                    document.getElementById("ocr_head").src = strhead;
                    document.getElementById("chip_head").src = strChipHead;
                    document.getElementById("pic").src = strpic;
                    console.log('Message state', websocket.readyState);
                    console.log(json.Param);

                    if (strwhite != '' && strwhite != null) {
                        imagestr += strwhite + '||' + strhead + '||' + strChipHead + '||' + strpic + '||';
                        $('.images').empty();
                        $('.images').html(imagestr);
                        if ($('.perpic').html() == '') $('.perpic').html(strpic);
                    }

                    if (json.Param.hasOwnProperty('English_first_name')) {
                        document.getElementById("name").innerHTML = json.Param.English_first_name;
                    }


                    if (json.Param.hasOwnProperty('First_Name')) {
                        document.getElementById("name").innerHTML = json.Param.First_Name + ' ' + json.Param.Family_Name;
                    }
                    if (json.Param.hasOwnProperty('Date_of_birth')) {
                        document.getElementById("dob").innerHTML = json.Param.Date_of_birth;
                    }
                    if (json.Param.hasOwnProperty('Date_of_Birth')) {
                        DOB = json.Param.National_ID.slice(1, -7);
                        day = DOB.slice(-2);
                        mon = DOB.slice(2, -2);
                        yr = DOB.slice(0, -4);
                        fullDOB = (day + '/' + mon + '/' + yr);
                        document.getElementById("dob").innerHTML = fullDOB;
                    }
                    if (json.Param.hasOwnProperty('Issuing_country_code')) {
                        document.getElementById("icc").innerHTML = json.Param.Issuing_country_code;
                    }
                    if (json.Param.hasOwnProperty('City')) {
                        document.getElementById("icc").innerHTML = json.Param.City;
                    }
                    if (json.Param.hasOwnProperty('Passport_number')) {
                        document.getElementById("mrz").innerHTML = json.Param.Passport_number;
                    }
                    if (json.Param.hasOwnProperty('National_ID')) {
                        if (json.Param.National_ID != '') document.getElementById("mrz").innerHTML = json.Param.National_ID;
                    }
                    if (json.Param.hasOwnProperty('Sex')) {
                        document.getElementById("sex").innerHTML = json.Param.Sex;
                    }
                    if (json.Param.hasOwnProperty('Gender')) {
                        document.getElementById("sex").innerHTML = json.Param.Gender;
                    }
                    if (json.Param.hasOwnProperty('Home_Address')) {
                        document.getElementById("address").innerHTML = json.Param.Home_Address;
                    }
                    if (json.Param.hasOwnProperty('Expity_Data')) {
                        document.getElementById("exdate").innerHTML = json.Param.Expity_Data;
                    }
                    if (json.Param.hasOwnProperty('Date_of_expiry')) {
                        document.getElementById("exdate").innerHTML = json.Param.Date_of_expiry;
                    }
                    if (json.Param.hasOwnProperty('Marital_status')) {
                        document.getElementById("mstat").innerHTML = json.Param.Marital_status;
                    }
                    if (json.Param.hasOwnProperty('Profession')) {
                        document.getElementById("job").innerHTML = json.Param.Profession;
                    }
                    if (json.Param.hasOwnProperty('Religion')) {
                        document.getElementById("relg").innerHTML = json.Param.Religion;
                    }
                    if (json.Param.hasOwnProperty('Issuing_Data')) {
                        document.getElementById("isdate").innerHTML = json.Param.Issuing_Data;
                    }
                    //console.log(json);
                    //alert(json.Param.Passport_type);

                    document.getElementById("name").style.fontSize = '18px';
                    document.getElementById("dob").style.fontSize = '18px';
                    document.getElementById("icc").style.fontSize = '18px';
                    document.getElementById("mrz").style.fontSize = '18px';
                    document.getElementById("sex").style.fontSize = '18px';
                    document.getElementById("address").style.fontSize = '18px';
                    document.getElementById("job").style.fontSize = '18px';
                    document.getElementById("relg").style.fontSize = '18px';
                    document.getElementById("mstat").style.fontSize = '18px';
                    document.getElementById("isdate").style.fontSize = '18px';
                    document.getElementById("exdate").style.fontSize = '18px';

                    document.getElementById("name").style.color = '#000';
                    document.getElementById("dob").style.color = '#000';
                    document.getElementById("icc").style.color = '#000';
                    document.getElementById("mrz").style.color = '#000';
                    document.getElementById("sex").style.color = '#000';
                    document.getElementById("address").style.color = '#000';
                    document.getElementById("job").style.color = '#000';
                    document.getElementById("relg").style.color = '#000';
                    document.getElementById("mstat").style.color = '#000';
                    document.getElementById("isdate").style.color = '#000';
                    document.getElementById("exdate").style.color = '#000';

                    $('.save').prop('disabled', false);
                    $('.finish').prop('disabled', false);

                }
                if (json.Param.DESC.indexOf('Failed') !== -1) {
                    document.getElementById("white_picture").style.transform = 'rotate(180deg)';
                } else {

                }

            }


            websocket.onclose = function () {
                msg.innerHTML = "Socket state: " + readyState[websocket.readyState];
                console.log('close state', websocket.readyState);
            }
            msg = document.getElementById("msg");
            msg.innerHTML = "Socket state: " + readyState[websocket.readyState];
            console.log('main state', websocket.readyState);
        } catch (exception) {
            msg.innerHTML = "Error";
        }
    }

    function send() {
        var msg = document.getElementById("msg");
        var text = document.getElementById("text").value;
        if (text == "") {
            msg.innerHTML = "Please enter some text";
            return;
        }
        try {
            websocket.send(text);
            msg.innerHTML = "send data: " + text + "";
        } catch (exception) {
            msg.innerHTML = "Error sending data";
        }
    }

    function disconnect() {
        websocket.close();
        msg.innerHTML = "Close connection";
    }

    function padNumber(num, fill) {
        var len = ('' + num).length;
        return (Array(fill > len ? (fill - len + 1) || 0 : 0).join(0) + num);
    }


</script>
