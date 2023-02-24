<?php
namespace App\Http\Requests;
use App\Http\Requests\Request;

class CriterioFormRequest extends Request
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
    public function rules()
    {
        return [
            'nomcriterio'  => 'required|max:50',
            'tipo' => 'required|max:50',
            'regulado' => 'required|max:50',
        ];

    }
   
   

}
