<?php

declare(strict_types=1);

namespace App\Traits;

use App\Rules\GroupRules\MinItemsRule;
use App\Rules\GroupRules\MinTotalSizeInKbRule;
use App\Rules\ItemRules\AttributeRule;
use App\Rules\UploadedMediaRules;

/** @var \Illuminate\Foundation\Http\FormRequest $this */
trait ValidatesMedia
{
    /** @var array<array-key, mixed> */
    protected array $rewrittenRules;

    public function validateResolved(): void
    {
        $this->prepareForValidation();

        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $validator = $this->getValidatorInstance();

        // @phpstan-ignore-next-line
        $rules = $validator->getRules();

        $this->rewrittenRules = $this->moveItemRulesToMediaItems($rules);

        // @phpstan-ignore-next-line
        $validator->setRules($this->rewrittenRules);

        if ($validator->fails()) {
            $this->failedValidation($validator);
        }

        $this->passedValidation();
    }

    /**
     * @param  array<array-key, mixed>  $rules
     * @return array<array-key, mixed>
     */
    public function moveItemRulesToMediaItems(array $rules): array
    {
        [$itemRules, $remainingRules] = $this->filterItemRules($rules);

        return array_merge($remainingRules, $itemRules);
    }

    /**
     * @param  array<array-key, mixed>  $allAttributeRules
     * @return array<array-key, mixed>
     */
    public function filterItemRules(array $allAttributeRules): array
    {
        $itemRules = [];
        $remainingRules = [];

        foreach ($allAttributeRules as $attribute => $attributeRules) {
            $remainingRules[$attribute] = [];

            if (is_string($attributeRules)) {
                $remainingRules[$attribute] = $allAttributeRules;

                continue;
            }

            foreach ($attributeRules as $attributeRule) {
                if (is_string($attributeRule)) {
                    $remainingRules[$attribute][] = $attributeRule;
                } elseif ($attributeRule instanceof UploadedMediaRules) {
                    foreach ($attributeRule->groupRules as $groupRule) {
                        $remainingRules[$attribute][] = $groupRule;
                    }

                    foreach ($attributeRule->itemRules as $itemRule) {
                        if ($itemRule instanceof AttributeRule) {
                            $ruleAttribute = $itemRule->attribute;

                            $itemRules[sprintf('%s.*.%s', $attribute, $ruleAttribute)][] = $itemRule;
                        } else {
                            $itemRules["{$attribute}.*"][] = $itemRule;
                        }
                    }
                } else {
                    $remainingRules[$attribute][] = $attributeRule;
                }

                $minimumRuleUsed = collect($remainingRules[$attribute])->contains(function ($attributeRule): bool {
                    if (is_string($attributeRule)) {
                        return false;
                    }

                    if ($attributeRule instanceof MinItemsRule && $attributeRule->minItemCount) {
                        return true;
                    }

                    return $attributeRule instanceof MinTotalSizeInKbRule && $attributeRule->minTotalSizeInKb;
                });

                if ($minimumRuleUsed) {
                    $remainingRules[$attribute][] = 'required';
                }
            }
        }

        return [$itemRules, $remainingRules];
    }

    protected function validateSingleMedia(): UploadedMediaRules
    {
        return (new UploadedMediaRules)->maxItems(1);
    }

    protected function validateMultipleMedia(): UploadedMediaRules
    {
        return new UploadedMediaRules;
    }
}
