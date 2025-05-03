<?php

namespace Awcodes\Richie\Data;

class MentionItem
{
    public function __construct(
        public int $id,
        public string $label,
        public ?string $type = null,
        public ?string $href = null,
        public ?string $target = '_blank',
        public ?string $image = null,
        public bool $roundedImage = false,
        public array $data = []
    ) {}

    /**
     * Converts the object properties to an associative array.
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'id' => $this->id,
            'type' => $this->type,
            'href' => $this->href,
            'target' => $this->target,
            'image' => $this->image,
            'roundedImage' => $this->roundedImage,
            'data' => $this->data,
        ];
    }

    /**
     * Converts the object properties to a JSON string.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
