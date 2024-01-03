<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    @php
                    $count=count($breadcrumb);
                    $i=1;
                    @endphp
                    @foreach($breadcrumb as $bc)
                    <li class="breadcrumb-item @if($i==$count) active @endif">
                        @if($i==$count)
                            {{ $bc['title'] }}
                            @else
                        <a href="#">{{ $bc['title'] }}</a>
                            @endif
                    </li>
                        @php $i++; @endphp
                    @endforeach
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
