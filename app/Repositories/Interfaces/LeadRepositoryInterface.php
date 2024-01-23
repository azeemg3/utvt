<?php

namespace App\Repositories\Interfaces;

interface LeadRepositoryInterface
{
    public function store($data);
    public function edit($id);
    public function check_lead($mobile_number);
    public function lead_boxes($status);
    public function show($id);
    public function destroy($id);
    public function takeover_lead($data);
    public function lead_conversation($data,$id);
    public function change_status($id,$status);
    public function lead_reason($data);
}
