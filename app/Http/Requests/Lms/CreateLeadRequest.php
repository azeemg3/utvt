<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $array1=[];
        $array= [
            'mobile' => 'required|min:9',
            'contact_name' => 'required',
            'CID' => 'required',
            'source_id' => 'required',
        ];
        if($this->input('type')==2){
            $sourceId = $this->input('source_id');
            $source = $sourceId ? \App\Models\Lms\SourceQuery::find($sourceId) : null;
            $name = strtolower((string) optional($source)->name);
            $isOpenChannel = str_contains($name, 'facebook')
                || str_contains($name, 'website')
                || str_contains($name, 'free quote');
            if (!$isOpenChannel) {
                $array1=['spo' => 'required'];
            }
        }
        return array_merge($array,$array1);

    }
    public function messages()
    {
        return ['CID.required' => 'Country Required'];
    }
}
