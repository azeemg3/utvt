<div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <label>Contact Name:</label>
                <input type="text" class="form-control form-control-sm" placeholder="Contact Name..."
                    name="contact_name" value="{{ $businessContact->contact_name??"" }}">
            </div>
            <div class="col-md-6">
                <label>Mobile:</label>
                <input type="text" class="form-control form-control-sm" placeholder="Contact Mobile..."
                    name="contact_mobile" value="{{ $businessContact->contact_mobile??"" }}">
            </div>
        </div>
        <!--row-->
        <div class="row">
            <div class="col-md-6">
                <label class="col-sm-4">What's App:</label>
                <input type="text" class="form-control form-control-sm" name="contact_wahts_app"
                 placeholder="Contact What's App" value="{{ $businessContact->contact_wahts_app??"" }}">
            </div>
            <div class="col-md-6">
                <label>Email:</label>
                <input type="text" class="form-control form-control-sm" placeholder="Business Name..."
                    name="contact_email" value="{{ $businessContact->contact_email??"" }}">
            </div>
        </div>
        <!--row-->
        <div class="row">
            <div class="col-md-6">
                <label>Country:</label>
                <select class="form-control form-control-sm" name="contact_country">
                    <option value="">Option-1</option>
                    {!! App\Models\Country::dropdown($businessContact->contact_country??"") !!}
                </select>
            </div>
            <div class="col-md-6">
                <label>City:</label>
                <select class="form-control form-control-sm" name="contact_city">
                    <option value="">Option-1</option>
                </select>
            </div>
            <div class="col-md-12">
                <label></label>
                    <input type="text" name="contact_address" class="form-control form-control-sm"
                    placeholder="Contact Address" value="{{ $businessContact->contact_address??"" }}">
            </div>
            <div class="col-md-12">
                <label></label>
                <textarea name="contact_other_details" class="form-control" placeholder="Other Details.."
                value="{{ $businessContact->contact_other_details??"" }}"></textarea>
            </div>
        </div>
        <!--row-->
    </div>
    <!--col-->
</div>
