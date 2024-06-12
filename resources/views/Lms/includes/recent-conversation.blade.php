<style>
    .note-editor {
        width: 100% !important;
    }
</style>
<div class="tab-pane active" id="recent-conversation">
    <!-- Post -->
    <div class="post clearfix">
        @if ($conversation)
            <div class="user-block">
                <img class="img-circle img-bordered-sm" src="{{ asset('dist/img/user7-128x128.jpg') }}" alt="User Image">
                <span class="username">
                    <a href="#" id="conversation-by">{{ $conversation->user->name }}</a>
                </span>
                <span class="description"><i class="far fa-clock"></i> - <span
                        id="conversation-time">{{ $conversation->created_at }}</span></span>
            </div>
            <!-- /.user-block -->
            <p id="conversation-message">{!! $conversation->conversation !!}</p>
        @else
            <div class="user-block">
                <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                <span class="username">
                    <a href="#" id="conversation-by">{{ $data->createdBy->name??"" }}</a>
                </span>
                <span class="description"><i class="far fa-clock"></i> - <span
                        id="conversation-time">{{ $data->created_at }}</span></span>
            </div>
            <!-- /.user-block -->
            <p id="conversation-message">{!! $data->other_details !!}</p>
        @endif
        <form class="form-horizontal" id="lead-conversation-form">
            <input type="hidden" name="leadId" id="leadId" value="{{ $data->id }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Contact Through</label>
                        <select class="form-control form-control-sm" id="contact_via" name="contact_via">
                            <option value="phone">Phone</option>
                            <option value="whatsapp">What's App</option>
                            <option value="email">Email</option>
                        </select>
                    </div>
                </div>
                <!--col-->
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Add in Box</label>
                        <select class="form-control form-control-sm select2" id="BOXID" name="BOXID">
                            @foreach (Helpers::lead_boxes() as $key=>$val)
                                <option value="{{$key}}" @if($data->BOXID==$key) selected @endif>{{strtoupper($val)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!--col-->
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <label style="visibility: hidden">Set Reminder</label>
                            <input  type="checkbox" class="custom-control-input" id="reminder-button">
                            <label class="custom-control-label" for="reminder-button">Reminder?</label>
                        </div>
                    </div>
                </div>
                <!--col-->
                <div class="col-md-2 reminder" style="display: none">
                    <div class="form-group">
                        <label>Reminder Date</label>
                        <input type="text" class="form-control form-control-sm date" id="reminder_date" placeholder="{{ date('m-d-Y') }}" name="reminder_date">
                    </div>
                </div>
                <!--col-->
                <div class="col-md-2 reminder" style="display: none">
                    <div class="form-group">
                        <label>Time picker:</label>
                       <input type="time" class="from-control form-control-sm" id="reminder_time" name="reminder_time">
                      </div>
                </div>
                <!--col-->
                <div class="input-group input-group-sm mb-0">
                    <textarea class="form-control form-control-sm textarea" id="message" name="message" placeholder="Type Message....."></textarea>
                </div><br>
                <button type="button" onClick="lead_conversation()" class="btn btn-success float-right">Send</button>
            </div>
            <!--row-->
        </form>
    </div>
    <!-- /.post -->
</div>
<!-- /.tab-pane -->
