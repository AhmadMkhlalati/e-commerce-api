<?php
namespace App\Actions\Taxses;


use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;

class CalculateTax
{
    public function __construct(float $price, int | Tax $taxId,$allTaxes = null,$allTaxComponents = null)
    {
        $this->price = $price;
        $this->tax_id = is_int($taxId) ? $taxId : $taxId->id;
        $this->all_taxses = is_null($allTaxes) ? Tax::all() : $allTaxes;
        $this->all_tax_components = is_null($allTaxes) ? TaxComponent::all() : $allTaxComponents;
    }

    public function handle(): float|int
    {

        $taxAmount = $this->calculateTaxAmountFromNonTTCPrice($this->price, $this->tax_id, $this->all_taxses, $this->all_tax_components);
        $ttcPrice = $this->price + $taxAmount;
        $totalTaxPercent = ($taxAmount * 100) / $this->price;
        echo "<b>Total Tax of {$this->price} is {$taxAmount}, TTC Price = {$ttcPrice} | Total Tax Percentage: {$totalTaxPercent}</b><br/>";
        echo "------------------------ ------------------------ ------------------------ ------------------------<br/>";

        $taxAmount = $this->calculateTaxAmountFromTTCPrice($ttcPrice, $this->tax_id, $this->all_taxses, $this->all_tax_components);
        $nonTtcPrice = $ttcPrice - $taxAmount;
        $totalTaxPercent = ($taxAmount * 100) / $nonTtcPrice;
        echo "<b>Total Tax in TTC Price {$ttcPrice} is {$taxAmount}, Non TTC Price = {$nonTtcPrice} | Total Tax Percentage: {$totalTaxPercent}</b><br/>";
        echo "<hr/><br/><br/>";

        return 0;
    }

    private function calculateTaxAmountFromTTCPrice(float $ttcPrice, int $taxId, $allTaxes, $allTaxComponents): float|int
    {
        $taxAmount = 0;
        $tax = $allTaxes->where('id',$taxId)->first();
        if (!$tax['is_complex']) {
            $taxAmount =  ($ttcPrice * $tax['percentage'] / (100 + $tax['percentage']));
            echo "Simple [{$tax['percentage']}%]: {$taxAmount}<br/>";
            return $taxAmount;
        }
        $components = $this->getTaxComponent($taxId, $allTaxes, $allTaxComponents);
        if ($tax['complex_behavior'] == 'after_other') {
            foreach (array_reverse($components, true) as $componentTax) {
                $componentTaxAmount = $this->calculateTaxAmountFromTTCPrice($ttcPrice, $componentTax['id'], $allTaxes, $allTaxComponents);
                $taxAmount += $componentTaxAmount;
                $ttcPrice -= $componentTaxAmount;
                echo "Complex After [{$componentTax['percentage']}%]: {$componentTaxAmount}<br/>";

            }
        } else {
            //since combine we can't get the tax one by one we must get the total percent
            $totalCombineTax = $this->calculateTotalTaxPercentageFromTTC($ttcPrice, $taxId, $allTaxes, $allTaxComponents);
            $componentTaxAmount = ($ttcPrice * $totalCombineTax / (100 + $totalCombineTax));
            $taxAmount += $componentTaxAmount;
            $ttcPrice -= $componentTaxAmount;
            echo "Complex Combine [Total {$totalCombineTax}%]: {$componentTaxAmount}<br/>";

        }
        return $taxAmount;
    }

    //to be used in the future
    private function calculateTaxAmountFromNonTTCPrice($price, $taxId, $allTaxes, $allTaxComponents): float|int
    {
        $taxAmount = 0;
        $tax = $allTaxes->where('id',$taxId)->first();

        if (!$tax['is_complex']) {
            $taxAmount = ($price * $tax['percentage'] / 100);
            return $taxAmount;
        }
        $components = $this->getTaxComponent($taxId, $allTaxes, $allTaxComponents);
        if ($tax['complex_behavior'] == 'after_other') {
            foreach ($components as $componentTax) {
                $componentTaxAmount = $this->calculateTaxAmountFromNonTTCPrice($price, $componentTax['id'], $allTaxes, $allTaxComponents);
                $taxAmount += $componentTaxAmount;
                $price += $componentTaxAmount; //so second component will get tax include the previous tax
                echo "Complex After [{$componentTax['percentage']}%]: {$componentTaxAmount}<br/>";

            }
        } else {
            foreach ($components as $componentTax) {
                $componentTaxAmount = $this->calculateTaxAmountFromNonTTCPrice($price, $componentTax['id'], $allTaxes, $allTaxComponents);
                $taxAmount += $componentTaxAmount;
                echo "Complex Combine [{$componentTax['percentage']}%]: {$componentTaxAmount}<br/>";
            }
        }
        return $taxAmount;
    }

    private function calculateTotalTaxPercentageFromTTC($ttcPrice, $taxId, $allTaxes, $allTaxComponents){
        $tax = $allTaxes->where('id',$taxId)->first();
        if (!$tax['is_complex']) {
            return $tax['percentage'];
        }
        if ($tax['complex_behavior'] == 'after_other') {
            $taxAmount = $this->calculateTaxAmountFromTTCPrice($ttcPrice, $taxId, $allTaxes, $allTaxComponents);
            return (($taxAmount * 100) / ($ttcPrice - $taxAmount));
        }
        $components = $this->getTaxComponent($taxId, $allTaxes, $allTaxComponents);
        $taxPercent = 0;
        foreach ($components as $componentTax) {
            $taxPercent += $this->calculateTotalTaxPercentageFromTTC($ttcPrice, $componentTax['id'], $allTaxes, $allTaxComponents);
        }
        return $taxPercent;
    }

    private function getTaxComponent($taxId, $allTaxes, $allTaxComponents): array
    {
        $data = [];
        foreach ($allTaxComponents as $component) {
            if ($component['tax_id'] == $taxId)
                $data[] = $allTaxes->where('id',$component['component_tax_id'])->first();
        }
        return ($data);
    }
}
