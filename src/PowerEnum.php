<?php

namespace PowerEnum;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

use function request;

trait PowerEnum
{
    public static function fromRequest(string $key, ?self $default = null): ?self
    {
        return request()->enum($key, static::class) ?? $default;
    }

    public static function rule(): Enum
    {
        return new Enum(static::class);
    }

    public static function count(): int
    {
        return count(self::cases());
    }

    public static function collect(): Collection
    {
        return new Collection(static::cases());
    }

    /**
     * @param  self|array<self>  $only
     * @param  self|array<self>  $except
     * @return array<string|int, string>
     */
    public static function options(self|array $only = [], self|array $except = []): array
    {
        $result = [];

        $isLabeled = method_exists(static::class, 'getLabel');

        if ($only) {
            $cases = static::only($only);
        } elseif ($except) {
            $cases = static::except($except);
        } else {
            $cases = static::cases();
        }

        foreach ($cases as $case) {
            $result[$case->value] = $isLabeled ? $case->getLabel() : Str::headline($case->name);
        }

        return $result;
    }

    /**
     * @param  self|array<self>  $only
     * @param  self|array<self>  $except
     * @return array<int, string>
     */
    public static function names(self|array $only = [], self|array $except = []): array
    {
        if ($only) {
            return array_column(static::only($only), 'name');
        }

        if ($except) {
            return array_column(static::except($except), 'name');
        }

        return array_column(static::cases(), 'name');
    }

    /**
     * @param  self|array<self>  $only
     * @param  self|array<self>  $except
     * @return array<int, string|int>
     */
    public static function values(self|array $only = [], self|array $except = []): array
    {
        if ($only) {
            return array_column(static::only($only), 'value');
        }

        if ($except) {
            return array_column(static::except($except), 'value');
        }

        return array_column(static::cases(), 'value');
    }

    /**
     * @param  self|array<self>  $cases
     */
    public static function only(self|array $cases): array
    {
        $cases = is_array($cases) ? $cases : func_get_args();

        return array_filter(static::cases(), fn (self $enum) => $enum->isAny($cases));
    }

    /**
     * @param  self|array<self>  $cases
     */
    public static function except(self|array $cases): array
    {
        $cases = is_array($cases) ? $cases : func_get_args();

        return array_filter(static::cases(), fn (self $enum) => $enum->isNotAny($cases));
    }

    public function is(self $enum): bool
    {
        return $this->value === $enum->value;
    }

    public function isNot(self $enum): bool
    {
        return ! $this->is($enum);
    }

    /**
     * Check if the current value is any of the given values.
     *
     * @param  self|array<self>  $cases
     */
    public function isAny(self|array $cases): bool
    {
        return in_array($this, is_array($cases) ? $cases : func_get_args());
    }

    /**
     * Check if the current value is NOT any of the given values.
     *
     * @param  self|array<self>  $cases
     */
    public function isNotAny(self|array $cases): bool
    {
        return ! in_array($this, is_array($cases) ? $cases : func_get_args());
    }
}
