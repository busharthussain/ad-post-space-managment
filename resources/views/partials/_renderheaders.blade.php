
<div class="table-responsive">
    <table id="myTable" class="table table-bordered post-table table-striped table-icon">
        <thead>
        <tr>
            @if(!empty($headers))
                @foreach($headers AS $key => $header)
                    @php
                        $width = '';
                        if(!empty($header['width']))
                            $width = 'width='.$header['width'];
                    @endphp
                    @if(!empty($header['isSorter']))
                        <th {!! $width !!} id="{!! $header['sorterKey'] !!}">{!! $header['name'] !!}<i class="sorting fa fa-fw fa-sort"></i></th>
                    @else
                        <th {!! $width !!}>{!! $header['name'] !!}</th>
                    @endif
                @endforeach
            @endif
        </tr>
        </tr>
        </thead>
        <tbody id="page-data">
            
        </tbody>
    </table>
</div>
