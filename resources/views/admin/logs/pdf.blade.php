<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    {{--    <meta charset="UTF-8">  --}}
{{--    <meta charset="utf-8">--}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">


{{--    <link rel="preconnect" href="https://fonts.googleapis.com">--}}
{{--    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>--}}
{{--    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">--}}



    <title>Visit Details Report</title>

    <style>
        /*body {*/
        /*    font-family: 'Scheherazade New', serif;*/
        /*    */
        /*    }*/
        body { font-family: DejaVu Sans, sans-serif; }
        table {
            width: 95%;
            border-collapse: collapse;
            margin: 50px auto;
        }

        /* Zebra striping */
        tr:nth-of-type(odd) {
            background: #eee;
        }

        th {
            background: #3498db;
            color: white;
            font-weight: bold;
        }

        td,
        th {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 18px;
        }


    </style>

</head>

<body>

<div style="width: 95%; margin: 0 auto;">
    {{--    <div style="width: 10%; float:left; margin-right: 20px;">--}}
    {{--        <img src="{{ public_path('assets/images/logo.png') }}" width="100%"  alt="">--}}
    {{--    </div>--}}
    <div style="width: 50%; float: left;">
        <h1>Visit Details Report </h1>
    </div>
</div>

<table style="position: relative; top: 50px;">
    <thead>
    <tr>
        {{--        <th>{{ __('files.Visit ID') }}</th>--}}
        {{--        <th>{{ __('files.Visitor Name') }}</th>--}}
        {{--        <th>{{ __('files.Visit Time') }}</th>--}}
        {{--        <th>{{ __('files.Visit Car Type') }}</th>--}}
        {{--        <th>{{ __('files.Shipment Type') }}</th>--}}
        {{--        <th>{{ __('files.Visit Type Number') }}</th>--}}
        {{--        <th>{{ __('files.Companions') }}</th>--}}

        <th>Visit ID</th>
        <th>Visitor Name</th>
        <th>Visit Time</th>
        <th>Visit Car Type</th>
        <th>Shipment Type</th>
        <th>Visit Type Number</th>
    </tr>
    </thead>
    <tbody>
    @foreach($visits as $visit)
        <tr>
            <td> {{$visit->id}} </td>
            <td> {{$visit->visitor->first_name}}    {{$visit->visitor->last_name}}  </td>
            <td> {{ \Illuminate\Support\Carbon::parse($visit->checkin_at)->format('H:m:s') }} </td>

            {{-- Visit Type --}}
            <td>
                @if($visit->car_type == 'T')
                    Truck
                @endif

                @if($visit->car_type == 'C')
                    Car
                @endif

                @if($visit->car_type == 'P')
                    Person
                @endif
            </td>

            {{-- Shipment Type --}}
            <td>
                @if($visit->car_type == 'T')
                    @if( !empty($visit->shipment_id) && $visit->shipment_id != 0)
                        {{$visit->shipment->name }}
                    @else
                        ---
                    @endif
                @else
                    ---
                @endif

            </td>

            {{-- Shipment Number --}}
            <td>
                @if($visit->car_type == 'T')
                    @if( !empty($visit->shipment_number) )
                        {{$visit->shipment_number }}
                    @else
                        ---
                    @endif
                @else
                    ---
                @endif
            </td>


        </tr>
    @endforeach

    </tbody>
</table>
</body>
</html>
