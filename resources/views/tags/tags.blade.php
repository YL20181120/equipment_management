<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            width: 100%;
        }

        table tr td {
            vertical-align: middle;
            text-align: center;
            width: 180px;
            font-size: 12px;
        }

        table tr td svg {
            height: 48px;
            width: auto;
        }
    </style>
</head>
<body>
<?php
/** @var \App\EquipmentDetail[] $equipment */
?>
<table>
    @foreach($equipment as $item)
        <tr>
            @foreach($item as $itemin)
                <td>
                    {!! $itemin->tag !!}
                    <br>
                    {{ sprintf('%010d', $itemin->id) }}
                    <br>
                    [{{ $itemin->warehouse->name }}/{{ $itemin->equipment->name }}/{{$itemin->equipment->code}}]
                    <br>
                    NEXT CHECK: {{ $itemin->check_date->format('Y年m月d日') }}
                </td>
            @endforeach
        </tr>
    @endforeach

</table>
<script !src="">
    window.print();
</script>
</body>
</html>
