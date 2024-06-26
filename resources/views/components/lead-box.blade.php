{{-- <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
        <span class="info-box-icon {{ $lead[1] }}"><i class="{{ $lead[3] }}"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">{{ $lead[2] }}</span>
            <span class="info-box-number">{{ $lead[0] }}</span>
        </div>
        <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
</div>
<!--col--> --}}
<div class="col-md-3 col-sm-6 col-12">
    <a data-id="{{$lead['key']}}" class="btn lead_action btn-app
    @if($lead['key']==0) bg-secondary @endif
    @if($lead['key']==1) bg-info @endif
    @if($lead['key']==2) bg-primary @endif
    @if($lead['key']==3) bg-dark @endif
    @if($lead['key']==4) bg-teal @endif
    @if($lead['key']==5) bg-secondary @endif
    @if($lead['key']==6) bg-purple @endif
    @if($lead['key']==7) bg-warning @endif
    @if($lead['key']==8) bg-gray @endif
    @if($lead['key']==9) bg-warning @endif
    @if($lead['key']==10) bg-dark @endif
    @if($lead['key']==11) bg-default @endif
    @if($lead['key']==12) bg-teal @endif
    @if($lead['key']==13) bg-info @endif
    @if($lead['key']==14) bg-danger @endif
    @if($lead['key']==15) bg-primary @endif
    @if($lead['key']==16) bg-success @endif
    @if($lead['key']==17) bg-success @endif
    @if($lead['key']==18) bg-gradient-maroon @endif
    " style="width: 100%">
        <span class="badge bg-teal">
            @if($lead['key']==0) {{$lead['box0']}}
            @elseif($lead['key']==1) {{$lead['box1']}}
            @elseif($lead['key']==2) {{$lead['box2']}}
            @elseif($lead['key']==3) {{$lead['box3']}}
            @elseif($lead['key']==4) {{$lead['box4']}}
            @elseif($lead['key']==5) {{$lead['box5']}}
            @elseif($lead['key']==6) {{$lead['box6']}}
            @elseif($lead['key']==7) {{$lead['box7']}}
            @elseif($lead['key']==8) {{$lead['box8']}}
            @elseif($lead['key']==9) {{$lead['box9']}}
            @elseif($lead['key']==10) {{$lead['box10']}}
            @elseif($lead['key']==11) {{$lead['box11']}}
            @elseif($lead['key']==12) {{$lead['box12']}}
            @elseif($lead['key']==13) {{$lead['box13']}}
            @elseif($lead['key']==14) {{$lead['box14']}}
            @elseif($lead['key']==15) {{$lead['box15']}}
            @elseif($lead['key']==16) {{$lead['box16']}}
            @elseif($lead['key']==17) {{$lead['box17']}}
            @elseif($lead['key']==18) {{$lead['box18']}}
            @elseif($lead['key']==20) {{$lead['box20']}}
            @else 0
            @endif
        </span>
        <i class="fas fa-{{$lead['icon']}}"></i>{{ $lead['name']}}
        </a>
    </div>
