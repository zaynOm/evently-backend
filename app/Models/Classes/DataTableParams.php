<?php

namespace App\Models\Classes;

class DataTableParams
{
    public bool $checkPermission = true;

    public ?string $orderColumn;

    public ?string $orderDir;

    public ?object $filterParam;

    public function __construct(?array $orderParam = null, ?string $filterParam = null, bool $checkPermission = true)
    {
        $this->orderColumn = $this->assignOrderParam($orderParam, 'column');
        $this->orderDir = $this->assignOrderParam($orderParam, 'dir');
        $this->filterParam = $this->assignFilterParam($filterParam);
        $this->checkPermission = $checkPermission;
    }

    public function assignOrderParam(?array $orderParam, string $key)
    {
        return is_array($orderParam) && array_key_exists($key, $orderParam) ? $orderParam[$key] : null;
    }

    public function assignFilterParam(?string $filterParam = null)
    {
        if (! is_string($filterParam) || ! json_validate($filterParam)) {
            return null;
        }

        $decoded = json_decode($filterParam);

        if (empty($decoded) || (! property_exists($decoded, 'items') || empty($decoded->items))) {
            return null;
        }

        return $decoded;
    }

    public function hasOrderParam()
    {
        return ! is_null($this->orderColumn) && ! is_null($this->orderDir);
    }

    public function hasFilterParam()
    {
        return ! is_null($this->filterParam);
    }
}
