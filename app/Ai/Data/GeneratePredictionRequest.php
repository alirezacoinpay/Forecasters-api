<?php

namespace App\Ai\Data;

class GeneratePredictionRequest
{
    public function __construct(
        public ?string $language = null,
        public ?string $region = null,
        public ?string $topic = null,
        public ?string $category = null,
        public ?string $additionalInstructions = null,
    ) {}
}
