<?php

namespace App\Data\Request\Freepik;

use App\Enums\Freepik\KlingModelEnum;
use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class KlingImageToVideoData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public ?string $image;

    #[Computed]
    public ?string $image_tail;

    #[Computed]
    public ?string $static_mask;

    public function __construct(
        public KlingModelEnum $model,
        public string $duration,
        #[Hidden]
        public ?string $image_cid,
        #[Hidden]
        public ?string $image_tail_cid,
        public ?string $prompt,
        public ?string $negative_prompt,
        public ?float $cfg_scale,
        #[Hidden]
        public ?string $static_mask_cid,
        public ?array $dynamic_masks,
    ) {
        if ($image_cid) {
            $this->image = $this->getPublishUrl($image_cid);
        }

        if ($image_tail_cid) {
            $this->image_tail = $this->getPublishUrl($image_tail_cid);
        }

        if ($static_mask_cid) {
            $this->static_mask = $this->getPublishUrl($static_mask_cid);
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (!empty($data['dynamic_masks'])) {
            $data['dynamic_masks'] = array_map(function ($mask) {
                if (!empty($mask['mask_cid'])) {
                    $mask['mask'] = $this->getPublishUrl($mask['mask_cid']);
                    unset($mask['mask_cid']);
                }

                return $mask;
            }, $data['dynamic_masks']);
        }

        return $data;
    }
}
