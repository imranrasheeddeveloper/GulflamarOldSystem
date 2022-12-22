<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;


class addEmployeeValidate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    protected function prepareForValidation()
    {
        $input =  $this->all();
        if (array_key_exists('vendor', $input)) {
            $input['vendor'] = json_decode($input['vendor']);
            $input['vendor'] =  $this->convertObject($input['vendor']);

            //dd($input['vendor']);
            if ($input['vendor'] != null && $input['vendor'] != '') {

                $this->merge(['vendor'=>$input['vendor']['value']]);
            }

        }

        if (array_key_exists('accommodation', $input)) {
            $input['accommodation'] = json_decode($input['accommodation']);
            $input['accommodation'] =  $this->convertObject($input['accommodation']);
            if ($input['accommodation'] != null && $input['accommodation'] != '') {
                
                $this->merge(['accommodation'=>(int)$input['accommodation']['value']]);
            }
        }

        if (array_key_exists('iqama_profession', $input)) {
            $input['iqama_profession'] = json_decode($input['iqama_profession']);
            $input['iqama_profession'] =  $this->convertObject($input['iqama_profession']);
            if ($input['iqama_profession'] != null && $input['iqama_profession'] != '') {

                $this->merge(['iqama_profession'=>$input['iqama_profession']['value']]);
            }
        }

        if (array_key_exists('client', $input)) {
            
        
            $input['client'] = json_decode($input['client']);
            $input['client'] =  $this->convertObject($input['client']);
            if ($input['client'] != null && $input['client'] != '') {
                
                $this->merge(['client'=>$input['client']['value']]);
            }
        }

       //dd($this->all());

    }

    function convertObject($data){

        if (is_array($data) || is_object($data))
        {
            $result = [];
            foreach ($data as $key => $value)
            {
                $result[$key] =  $value;
            }

            return $result;
            
        }

        return $data;
    }

    public function rules()
    {
        return [
            'name'   => 'required',
            'age'   => 'nullable|numeric',

            'contact_number'   => 'nullable|string|size:12',
            'iban'   => 'nullable|max:24',
            'iqama_no'   => 'nullable|numeric|max:9999999999',
            'salary_rate'   => 'nullable|numeric|max:9999999999999',
           
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message'=>$validator->errors()->first(),'success' => false,'data' => []], 200));
    }
}
