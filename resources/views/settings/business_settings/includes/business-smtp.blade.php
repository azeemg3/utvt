<div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel"
                                        aria-labelledby="vert-tabs-messages-tab">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Mail Host<span>*</span></label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            placeholder="Mail Host..." name="mail_host" value="{{ $businessSmtp->mail_host ??''}}" >
                                                    </div>
                                                </div>
                                                <!--col-->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Mail Port<span>*</span></label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            placeholder="Mail Port..." name="mail_port" value="{{ $businessSmtp->mail_host ??''}}">
                                                    </div>
                                                </div>
                                                <!--col-->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Mail Address<span>*</span></label>
                                                        <input type="email" class="form-control form-control-sm"
                                                            placeholder="Mail Address..." name="mail_address" value="{{ $businessSmtp->mail_address ??''}}">
                                                    </div>
                                                </div>
                                                <!--col-->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Password<span>*</span></label>
                                                        <input type="password" class="form-control form-control-sm"
                                                            placeholder="Password..." name="mail_password" value="{{ $businessSmtp->mail_password ??''}}">
                                                    </div>
                                                </div>
                                                <!--col-->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Mail From Name<span>*</span></label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            placeholder="Mail From Name..." name="mail_from" value="{{ $businessSmtp->mail_from ??''}}">
                                                    </div>
                                                </div>
                                                <!--col-->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Encryption(SSL/TSL)<span>*</span></label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            placeholder="SSL/TSL..." name="mail_encryption" value="{{ $businessSmtp->mail_encryption ??''}}">
                                                    </div>
                                                </div>
                                                <!--col-->
                                            </div>
                                            <!--row-->
                                        </div>
                                        <!--col-->
                                    </div>
