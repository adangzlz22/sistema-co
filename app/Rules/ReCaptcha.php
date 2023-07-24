<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ReCaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // $response = Http::get("https://www.google.com/recaptcha/api/siteverify",[
        //     'secret' => env('GRS'),
        //     'response' => $value
        // ]);
        try {
            $data = http_build_query(array(
                'secret' => env('GRS'),
                'response' => $value
              ));
               
              $curl = curl_init();
                 
              $captcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";
               
              curl_setopt($curl, CURLOPT_URL,$captcha_verify_url);
              curl_setopt($curl, CURLOPT_POST, true);
              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
               
               
              $captcha_output = curl_exec($curl);
              curl_close($curl);
              $decoded_captcha = json_decode($captcha_output);
              return isset($decoded_captcha->success)?$decoded_captcha->success:false;
        } catch (\Throwable $th) {
            curl_close($curl);
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El recaptcha es requerido.';
    }
}
