@php
    $cb = App\Models\Lead::recent_action($data->id, 1);
    //taken over by
    $tob = App\Models\Lead::recent_action($data->id, 2);
    //in process
    $ip = App\Models\Lead::recent_action($data->id, 3);
    //successfull
    $sf = App\Models\Lead::recent_action($data->id, 4);
    //unsuccessfull
    $usf = App\Models\Lead::recent_action($data->id, 5);
@endphp
<div class="tab-pane" id="timeline">
    <!-- The timeline -->
    <div class="timeline timeline-inverse">
        @foreach ($lead_activity as $leadActivity)
            @if ($leadActivity->action_status == 2)
                <!-- timeline time label -->
                <div class="time-label">
                    <span class="bg-info">
                        {{ Helpers::string_day($leadActivity->created_at) }}
                    </span>
                </div>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <div>
                    <i class="fas fa-comment bg-info"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i>
                            {{ Helpers::fetch_time($leadActivity->created_at) }}</span>
                        <h3 class="timeline-header">
                            <a href="#" class="text-primary">Takenover By</a>
                            {{ $data->reopen_lead_by->name ?? '' }}
                        </h3>
                        <div class="timeline-body">
                            Lead Takenover {{ Helpers::calculateDateTimeDifference($data->created_at, $leadActivity->created_at) }} ago
                        </div>
                    </div>
                </div>
            @endif
            <!-- END timeline item -->
            @if ($leadActivity->action_status == 3)
                <!-- timeline time label -->
                <div class="time-label">
                    <span class="bg-info">
                        {{ Helpers::string_day($leadActivity->created_at) }}
                    </span>
                </div>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <div>
                    <i class="fas fa-comment bg-info"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i>
                            {{ Helpers::fetch_time($leadActivity->created_at) }}</span>
                        <h3 class="timeline-header">
                            <a class="text-info" href="#">Inprocess</a>
                        </h3>
                        <div class="timeline-body">
                            Lead In Process from
                            {{ Helpers::calculateDateTimeDifference($tob->created_at, $leadActivity->created_at) }} ago
                        </div>
                    </div>
                </div>
            @endif
            <!-- END timeline item -->
            @if ($leadActivity->action_status == 4)
                <!-- timeline time label -->
                <div class="time-label">
                    <span class="bg-success">
                        {{ Helpers::string_day($leadActivity->created_at) }}
                    </span>
                </div>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <div>
                    <i class="fas fa-comment bg-success"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i>
                            {{ Helpers::fetch_time($leadActivity->created_at) }}</span>
                        <h3 class="timeline-header">
                            <a class="text-success">Successfull</a>
                        </h3>
                        <div class="timeline-body">
                            Lead Susccessfull {{ Helpers::calculateDateTimeDifference($data->created_at, $leadActivity->created_at) }} ago
                        </div>
                    </div>
                </div>
            @endif
            <!-- END timeline item -->
            @if ($leadActivity->action_status == 5)
                <!-- timeline time label -->
                <div class="time-label">
                    <span class="bg-info">
                        {{ Helpers::string_day($leadActivity->created_at) }}
                    </span>
                </div>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <!-- timeline item -->
                <div>
                    <i class="fas fa-comment bg-danger"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="far fa-comment"></i>
                            {{ Helpers::string_day($usf->created_at) }}</span>
                        <h3 class="timeline-header">
                            <a class="text-danger" href="#">Unsuccessfull</a>
                        </h3>
                        <div class="timeline-body">
                            Lead Unsuccessfull
                            {{ Helpers::calculateDateTimeDifference($usf->created_at, $usf->created_at) }} ago
                            Unsuccessfull Reason
                        </div>
                    </div>
                </div>
                <!-- END timeline item -->
            @endif
            <!-- END timeline item -->
            @if ($leadActivity->action_status == 1)
            <!-- timeline time label -->
        <div class="time-label">
            <span class="bg-secondary">
                {{ Helpers::string_day($cb->created_at) }}
            </span>
        </div>
        <!-- /.timeline-label -->
        <!-- timeline item -->
        <div>
            <i class="fas fa-comment bg-secondary"></i>
            <div class="timeline-item">
                <span class="time"><i class="far fa-clock"></i> {{ Helpers::fetch_time($cb->created_at) }}</span>
                <h3 class="timeline-header">
                    <a class="text-secondary" href="#">Created By</a>
                    {{ $cb->user->name ?? '' }}
                </h3>
                <div class="timeline-body">
                    Lead Created with the reference of {{ Helpers::fetch_lead_source($data->source_id) }}
                    at {{ Helpers::date_format($data->created_at) }}
                </div>
            </div>
        </div>
        <!-- END timeline item -->
        @endif
        @endforeach
        <div>
            <i class="far fa-clock bg-gray"></i>
        </div>
    </div>
</div>
<!-- /.tab-pane -->
