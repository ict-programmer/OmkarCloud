<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use MongoDB\BSON\ObjectId;

trait MongoObjectIdTrait
{
    /**
     * Generate a new MongoDB ObjectId
     *
     * @return ObjectId A new ObjectId instance
     */
    protected function newObjectId(): ObjectId
    {
        return new ObjectId();
    }

    /**
     * Convert various input types to MongoDB ObjectId
     *
     * @param  mixed  $value  The value to convert to ObjectId
     * @return ObjectId The converted ObjectId
     *
     * @throws InvalidArgumentException When the value cannot be converted to ObjectId
     */
    protected function toObjectId(mixed $value): ?ObjectId
    {
        try {
            if ($value instanceof ObjectId) {
                return $value;
            }

            if ($value === null) {
                return null;
            }

            if (empty($value)) {
                // Log the backtrace to help debug where this is coming from
                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
                Log::warning('Empty value passed to toObjectId', [
                    'value' => $value,
                    'backtrace' => array_map(function ($trace) {
                        return [
                            'file' => $trace['file'] ?? 'unknown',
                            'line' => $trace['line'] ?? 'unknown',
                            'function' => $trace['function'] ?? 'unknown',
                        ];
                    }, $backtrace),
                ]);

                return null;
            }

            if (is_string($value)) {
                $value = trim($value);

                // Check for invalid values
                if (in_array(strtolower($value), ['unspecified', 'null', 'undefined', 'none'])) {
                    Log::warning('Invalid ObjectId value encountered', ['value' => $value]);

                    return null;
                }

                if (preg_match('/^[0-9a-f]{24}$/i', $value)) {
                    return new ObjectId($value);
                }

                if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value)) {
                    return new ObjectId($value);
                }

                throw new InvalidArgumentException("Invalid ObjectId string format: {$value}");
            }

            if (is_numeric($value)) {
                $stringValue = (string) $value;
                if (preg_match('/^[0-9a-f]{24}$/i', $stringValue)) {
                    return new ObjectId($stringValue);
                }
                throw new InvalidArgumentException("Invalid ObjectId numeric format: {$value}");
            }

            if (is_array($value)) {
                if (isset($value['_id'])) {
                    return $this->toObjectId($value['_id']);
                }
                if (isset($value['id'])) {
                    return $this->toObjectId($value['id']);
                }
                throw new InvalidArgumentException('Array does not contain _id or id field');
            }

            if (is_object($value)) {
                if (property_exists($value, '_id')) {
                    return $this->toObjectId($value->_id);
                }
                if (property_exists($value, 'id')) {
                    return $this->toObjectId($value->id);
                }
                throw new InvalidArgumentException('Object does not contain _id or id property');
            }

            $type = gettype($value);
            throw new InvalidArgumentException("Cannot convert {$type} to ObjectId");
        } catch (\Exception $e) {
            Log::error('Failed to convert value to ObjectId', [
                'value' => $value,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            throw $e;
        }
    }

    /**
     * Check if a string is a valid MongoDB ObjectId
     *
     * @param  string  $id  The string to check
     * @return bool True if the string is a valid ObjectId, false otherwise
     */
    public function isValidObjectId($id)
    {
        return preg_match('/^[a-f\d]{24}$/i', $id);
    }
}
