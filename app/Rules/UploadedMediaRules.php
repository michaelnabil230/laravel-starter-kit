<?php

declare(strict_types=1);

namespace App\Rules;

use App\Rules\GroupRules\MaxItemsRule;
use App\Rules\GroupRules\MaxTotalSizeInKbRule;
use App\Rules\GroupRules\MinItemsRule;
use App\Rules\GroupRules\MinTotalSizeInKbRule;
use App\Rules\ItemRules\AttributeRule;
use App\Rules\ItemRules\DimensionsRule;
use App\Rules\ItemRules\ExtensionRule;
use App\Rules\ItemRules\HeightBetweenRule;
use App\Rules\ItemRules\MaxItemSizeInKbRule;
use App\Rules\ItemRules\MimeTypeRule;
use App\Rules\ItemRules\MinItemSizeInKbRule;
use App\Rules\ItemRules\WidthBetweenRule;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;

final class UploadedMediaRules implements ValidationRule
{
    /** @var array<array-key, ValidationRule> */
    public array $groupRules = [];

    /** @var array<array-key, ValidationRule> */
    public array $itemRules = [];

    public function minItems(int $numberOfItems): self
    {
        $this->groupRules[] = new MinItemsRule($numberOfItems);

        return $this;
    }

    public function maxItems(int $numberOfItems): self
    {
        $this->groupRules[] = new MaxItemsRule($numberOfItems);

        return $this;
    }

    public function maxTotalSizeInKb(int $maxTotalSizeInKb): self
    {
        $this->groupRules[] = new MaxTotalSizeInKbRule($maxTotalSizeInKb);

        return $this;
    }

    public function minTotalSizeInKb(int $minTotalSizeInKb): self
    {
        $this->groupRules[] = new MinTotalSizeInKbRule($minTotalSizeInKb);

        return $this;
    }

    public function maxItemSizeInKb(int $maxSizeInKb): self
    {
        $this->itemRules[] = new MaxItemSizeInKbRule($maxSizeInKb);

        return $this;
    }

    public function minSizeInKb(int $minSizeInKb): self
    {
        $this->itemRules[] = new MinItemSizeInKbRule($minSizeInKb);

        return $this;
    }

    /**
     * @param  string[]|string  $mimes
     */
    public function mime(string|array $mimes): self
    {
        $this->itemRules[] = new MimeTypeRule($mimes);

        return $this;
    }

    /**
     * @param  string[]|string  $extensions
     */
    public function extension(string|array $extensions): self
    {
        $this->itemRules[] = new ExtensionRule($extensions);

        return $this;
    }

    /**
     * @param  string[]|string  $rules
     */
    public function itemName(array|string $rules): self
    {
        return $this->attribute('name', $rules);
    }

    /**
     * @param  string[]|string  $rules
     */
    public function attribute(string $attribute, array|string $rules): self
    {
        $this->itemRules[] = new AttributeRule($attribute, $rules);

        return $this;
    }

    /**
     * @param  string[]|string  $rules
     */
    public function customProperty(string $customPropertyName, string|array $rules): self
    {
        $customPropertyName = "custom_properties.{$customPropertyName}";

        $this->itemRules[] = new AttributeRule($customPropertyName, $rules);

        return $this;
    }

    public function dimensions(int $width, int $height): self
    {
        $this->itemRules[] = new DimensionsRule($width, $height);

        return $this;
    }

    public function width(int $width): self
    {
        $this->itemRules[] = new DimensionsRule($width, 0);

        return $this;
    }

    public function height(int $height): self
    {
        $this->itemRules[] = new DimensionsRule(0, $height);

        return $this;
    }

    public function widthBetween(int $minWidth, int $maxWidth): self
    {
        $this->itemRules[] = new WidthBetweenRule($minWidth, $maxWidth);

        return $this;
    }

    public function heightBetween(int $minHeight, int $maxHeight): self
    {
        $this->itemRules[] = new HeightBetweenRule($minHeight, $maxHeight);

        return $this;
    }

    /**
     * @param  string[]|string  $rules
     */
    public function customItemRules(string|array $rules): self
    {
        $this->itemRules = array_merge($this->itemRules, Arr::wrap($rules));

        return $this;
    }

    /**
     * @param  string[]|string  $rules
     */
    public function customGroupRules(string|array $rules): self
    {
        $this->groupRules = array_merge($this->groupRules, Arr::wrap($rules));

        return $this;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {}
}
