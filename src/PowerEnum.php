<?php

namespace PowerEnum;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use ValueError;

use function request;

trait PowerEnum
{
    /**
     * Returns the enum case from the enum name.
     * When the name is not valid, it returns null.
     */
    public static function tryFromName(string $name): ?self
    {
        $constant = self::class.'::'.$name;

        return defined($constant) ? constant($constant) : null;
    }

    /**
     * Returns the enum case from the enum name.
     *
     * @throws ValueError
     */
    public static function fromName(string $name): self
    {
        $result = self::tryFromName($name);

        if ($result === null) {
            throw new ValueError('"'.$name.'" is not a valid backing name for enum "'.static::class.'"');
        }

        return $result;
    }

    /**
     * Returns the enum case from the request.
     * Optionally, you can provide a default enum case.
     */
    public static function fromRequest(string $key, ?self $default = null): ?self
    {
        return request()->enum($key, static::class) ?? $default;
    }

    /**
     * Returns the validation rule for the enum.
     */
    public static function rule(): Enum
    {
        return new Enum(static::class);
    }

    /**
     * Counts the number of enum cases.
     */
    public static function count(): int
    {
        return count(self::cases());
    }

    /**
     * Returns the enum cases as a collection.
     *
     * @return Collection<self>
     */
    public static function collect(): Collection
    {
        return new Collection(static::cases());
    }

    /**
     * Returns the enum cases as an array of options.
     * Useful for select inputs.
     * Optionally, you can filter the options by passing the $only or $except parameters.
     *
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
     *  Returns the names of the enum cases.
     *  Optionally, you can filter the names by passing the $only or $except parameters.
     *
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
     * Returns the values of the enum cases.
     * Optionally, you can filter the values by passing the $only and $except parameters.
     *
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
     * Returns only the given cases.
     *
     * @param  self|array<self>  $cases
     * @return array<self>
     */
    public static function only(self|array $cases): array
    {
        $cases = is_array($cases) ? $cases : func_get_args();

        return array_filter(static::cases(), fn (self $enum) => $enum->isAny($cases));
    }

    /**
     * Returns all cases except the given ones.
     *
     * @param  self|array<self>  $cases
     * @return array<self>
     */
    public static function except(self|array $cases): array
    {
        $cases = is_array($cases) ? $cases : func_get_args();

        return array_filter(static::cases(), fn (self $enum) => $enum->isNotAny($cases));
    }

    /**
     * Checks if the current case is the given case.
     */
    public function is(self $enum): bool
    {
        return $this->value === $enum->value;
    }

    /**
     * Checks if the current case is NOT the given case.
     */
    public function isNot(self $enum): bool
    {
        return ! $this->is($enum);
    }

    /**
     * Checks if the current case is any of the given cases.
     *
     * @param  self|array<self>  $cases
     */
    public function isAny(self|array $cases): bool
    {
        return in_array($this, is_array($cases) ? $cases : func_get_args());
    }

    /**
     * Checks if the current case is NOT any of the given cases.
     *
     * @param  self|array<self>  $cases
     */
    public function isNotAny(self|array $cases): bool
    {
        return ! in_array($this, is_array($cases) ? $cases : func_get_args());
    }

    /**
     * Converts the enum name to lowercased string.
     */
    public function toLower(): string
    {
        return strtolower($this->name);
    }

    /**
     * Converts the enum name to uppercased string.
     */
    public function toUpper(): string
    {
        return strtoupper($this->name);
    }
}
