<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">在库临检设备</h3>

        <div class="box-tools pull-right">
            <a class="btn btn-box-tool"
               href="{{ route('admin.equipment-details.index', ['_sort[column]'=>'check_date','_sort[type]'=>'asc']) }}">更多
                >></a>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped">

                @foreach($equipment as $item)
                    <tr>
                        <td>{{ $item->warehouse->name }}</td>
                        <td>{{ $item->equipment->name }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->equipment->code }}</td>
                        <td>{{ $item->check_date->format('Y-m-d') }}</td>
                        <td>
                            @if ($item->is_in_stock)
                                <span style="color: green">在库</span>
                            @else
                                <span style="color: red">已出库</span>
                            @endif
                        </td>
                        <td>
                            @if (!$item->is_check)
                                <span style="color: green">
                                    {{ $item->check_date->diffForHumans() }}
                                </span>
                            @else
                                <span style="color: red">过期未检</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>
