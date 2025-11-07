<?php

namespace App\Http\Requests\FFMpeg;

use App\Models\ServiceProvider;
use App\Models\ServiceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use MongoDB\BSON\ObjectId;

class BatchProcessRequest extends FormRequest
{
    public array $services = [];

    public ServiceProvider|null $ffmpegProvider = null;

    public function rules(): array
    {
        return [
            'jobs' => 'required|array|min:1',
            'jobs.*.service_type_id' => 'required|string',
            'jobs.*.input_data' => 'required|array',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $this->ffmpegProvider = ServiceProvider::where('type', 'FFmpeg')->first();

            if (!$this->ffmpegProvider) {
                $validator->errors()->add('jobs', 'FFmpeg service provider not found');
            } else {
                foreach ($this->input('jobs', []) as $index => $job) {
                    $serviceType = ServiceType::where('service_provider_id', new ObjectId($this->ffmpegProvider->_id))
                        ->where('_id', new ObjectId($job['service_type_id']))->first();

                    if (!$serviceType) {
                        $validator->errors()->add(
                            "jobs.{$index}.service_type_id",
                            "Service type not found or does not belong to FFmpeg service provider"
                        );
                    } else {
                        $this->services[$index] = [
                            'service_type_id' => $serviceType->id,
                            'function_name' => $serviceType->function_name,
                            'request_class_name' => $serviceType->request_class_name,
                            'input_data' => $job['input_data'],
                        ];
                    }
                }
            }
        });
    }
}
