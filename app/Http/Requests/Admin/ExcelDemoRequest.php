<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiRequest;

class ExcelDemoRequest extends ApiRequest
{
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                return [
                    // CREATE ROLES
                    'str_column' => 'bail|required|string|max:255',
					'int_column' => 'bail|required|integer|min:0',
					'float_column' => 'bail|required|numeric|min:0',
					'pic_column' => 'bail|required|string|max:255',
					'text_column' => 'bail|required|string',
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    // UPDATE ROLES
                    'str_column' => 'bail|required|string|max:255',
					'int_column' => 'bail|required|integer|min:0',
					'float_column' => 'bail|required|numeric|min:0',
					'pic_column' => 'bail|required|string|max:255',
					'text_column' => 'bail|required|string',
                ];
            }
            case 'GET':
            {
                return [
                    // LIST ROLES
                    'page'=>'bail|required|integer|min:1',
                    'per_page'=>'bail|required|integer|min:1'
                ];
            }
            case 'DELETE':
            default:
            {
                return [];
            }
        }
    }

    public function messages()
    {
        return [
            // Validation messages
        ];
    }
}
