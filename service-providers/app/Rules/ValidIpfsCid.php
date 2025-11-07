<?php

namespace App\Rules;

use App\Traits\PubliishIOTrait;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ValidIpfsCid implements ValidationRule
{
    use PubliishIOTrait;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Step 1: Format validation (CIDv0 and basic CIDv1 support)
        if (!is_string($value) || !preg_match('/^(Qm[1-9A-HJ-NP-Za-km-z]{44}|baf[1-9A-Za-z]+)$/', $value)) {
            $fail('The :attribute is not a valid IPFS CID format.');

            return;
        }

        // Step 2: Resolve via IPFS gateway
        $url = $this->getPublishUrl($value);

        try {
            $response = Http::withOptions(['allow_redirects' => false])
                ->timeout(5)
                ->head($url);

            if (!$response->successful()) {
                $fail('The :attribute is not valid or cannot be found.');
            }
        } catch (\Exception $e) {
            $fail('The :attribute could not be verified due to a network error.');
        }
    }
}
