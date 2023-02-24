<?php
namespace App\Http\Requests;
use App\Http\Requests\Request;

class MaecanalesFormRequest extends Request
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
            'descrip'=>'required|max:50',
            'fecha'=>'required',
            'estado'=>'required|max:50', 
            'rif'=>'required|max:50',
            'direccion'=>'required|max:100',
            'telefono'=>'required|max:50', 
            'contacto'=>'required|max:50',
            'correo'=>'required|max:50',
            'zona'=>'required|max:50',
        ];
    }
}
