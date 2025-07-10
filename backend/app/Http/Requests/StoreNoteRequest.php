<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: changer à la connexion user
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // lettres, chiffres, espace,-_,.;:()!?'" (le u à lafin: support UTF-8indispensable pour les accents)
            'title' => 'required|string|min:3|max:150|regex:/^[\pL\pN\s\-_,\.;:()!?\'"]+$/u',
            'content' => 'required|string|min:2|max:255|regex:/^[\pL\pN\s\-_,\.;:()!?\'"]+$/u',
            'isFavorite' => 'nullable|boolean',
            'image' => 'nullable|image|max:2048', // img max 2 Mo (2048Ko)
            'category_id' => 'required|integer',
            'user_id' => 'required|integer'
        ];
    }

    /**
     * Message error
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.min' => 'Le titre doit contenir au moins :min caractères.',
            'title.max' => 'Le titre doit contenir au maximum :max caractères.',
            'title.regex' => 'Le titre contient des caractères non autorisés.',

            'content.required' => 'Le contenu est obligatoire.',
            'content.min' => 'Le contenu doit contenir au moins :min caractères.',
            'content.max' => 'Le contenu doit contenir au maximum :max caractères.',
            'content.regex' => 'Le contenu contient des caractères non autorisés.',

            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image doit faire moins de 2 Mo',
        ];
    }

    /**
     * Error validations
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Erreur de validation',
            'errors' => $validator->errors()
        ], 422));
    }
}
