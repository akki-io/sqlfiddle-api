<?php

namespace App\Http\Requests;

use function array_keys;
use function config;
use Illuminate\Foundation\Http\FormRequest;
use function implode;

class FiddleIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'connection' => 'required|in:'.implode(',', array_keys(config('database.connections'))),
            'schema' => 'required',
            'query' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
