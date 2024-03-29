<style>
    .direct-chat {
        width: 100%;
        /* Set width as per your requirement */
        background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        /* Replace 'your-image-url.jpg' with the path to your image */
        background-size: cover;
        /* This property ensures the image covers the entire div */
        background-position: center;
    }
</style>
<div class="tab-pane" id="whatsapp-conversation">
    <!-- The timeline -->
    <div class="card direct-chat direct-chat-primary">
        <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title">What's App Conversation</h3>
            <div class="card-tools">
                <span title="3 New Messages" class="badge badge-primary">3</span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                {{-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button> --}}
            </div>
        </div>

        <div class="card-body">

            <div class="direct-chat-messages">

                <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-left">{{ucfirst(Auth::user()->name)}}</span>
                        <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                    </div>

                    <img class="direct-chat-img" src="{{URL::asset('public/dist/img/user1-128x128.jpg')}}" alt="message user image">

                    <div class="direct-chat-text">
                        Is this template really for free? That's unbelievable!
                    </div>

                </div>
                <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-right">Client</span>
                        <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                    </div>
                    <img class="direct-chat-img" src="{{URL::asset('dist/img/user3-128x128.jpg')}}" alt="message user image">

                    <div class="direct-chat-text">
                        You better believe it!
                    </div>

                </div>
            </div>
            <div class="direct-chat-contacts">
                <ul class="contacts-list">
                    <li>
                        <a href="#">
                            <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Avatar">
                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                    Count Dracula
                                    <small class="contacts-list-date float-right">2/28/2015</small>
                                </span>
                                <span class="contacts-list-msg">How have you been? I was...</span>
                            </div>

                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <img class="contacts-list-img" src="dist/img/user7-128x128.jpg" alt="User Avatar">
                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                    Sarah Doe
                                    <small class="contacts-list-date float-right">2/23/2015</small>
                                </span>
                                <span class="contacts-list-msg">I will be waiting for...</span>
                            </div>

                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <img class="contacts-list-img" src="dist/img/user3-128x128.jpg" alt="User Avatar">
                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                    Nadia Jolie
                                    <small class="contacts-list-date float-right">2/20/2015</small>
                                </span>
                                <span class="contacts-list-msg">I'll call you back at...</span>
                            </div>

                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <img class="contacts-list-img" src="dist/img/user5-128x128.jpg" alt="User Avatar">
                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                    Nora S. Vans
                                    <small class="contacts-list-date float-right">2/10/2015</small>
                                </span>
                                <span class="contacts-list-msg">Where is your new...</span>
                            </div>

                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <img class="contacts-list-img" src="dist/img/user6-128x128.jpg" alt="User Avatar">
                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                    John K.
                                    <small class="contacts-list-date float-right">1/27/2015</small>
                                </span>
                                <span class="contacts-list-msg">Can I take a look at...</span>
                            </div>

                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <img class="contacts-list-img" src="dist/img/user8-128x128.jpg" alt="User Avatar">
                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                    Kenneth M.
                                    <small class="contacts-list-date float-right">1/4/2015</small>
                                </span>
                                <span class="contacts-list-msg">Never mind I found...</span>
                            </div>

                        </a>
                    </li>

                </ul>

            </div>

        </div>

        <div class="card-footer">
            <form action="#" method="post">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-primary">Send</button>
                    </span>
                </div>
            </form>
        </div>

    </div>
</div>
<!-- /.tab-pane -->
