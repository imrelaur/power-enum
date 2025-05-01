<?php

namespace Tests;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Enums\SocialLink;
use Tests\Enums\Status;

class PowerEnumTest extends TestCase
{
    /*
     * Rule
     */

    #[Test]
    public function it_returns_validation_rule()
    {
        $rule = Status::rule();

        self::assertInstanceOf(Enum::class, $rule);
    }

    /*
     * Options
     */

    #[Test]
    public function it_returns_labeled_options()
    {
        self::assertTrue(method_exists(Status::class, 'getLabel'));

        $expected = Arr::mapWithKeys(Status::cases(), fn ($case) => [$case->value => $case->getLabel()]);
        $result = Status::options();
        $count = count(Status::cases());

        self::assertCount($count, $result);
        self::assertSame($expected, $result);
    }

    #[Test]
    public function it_returns_named_options()
    {
        self::assertFalse(method_exists(SocialLink::class, 'getLabel'));

        $expected = Arr::mapWithKeys(SocialLink::cases(), fn ($case) => [$case->value => Str::headline($case->name)]);
        $result = SocialLink::options();
        $count = count(SocialLink::cases());

        self::assertCount($count, $result);
        self::assertSame($expected, $result);
    }

    #[Test]
    public function it_returns_only_options()
    {
        $result1 = SocialLink::options(only: SocialLink::Blog);
        $result2 = SocialLink::options(only: [SocialLink::Blog]);

        self::assertCount(1, $result1);
        self::assertContains(SocialLink::Blog->name, $result1);
        self::assertContains(SocialLink::Blog->name, $result2);
    }

    #[Test]
    public function it_returns_except_options()
    {
        $result1 = SocialLink::options(except: SocialLink::Blog);
        $result2 = SocialLink::options(except: [SocialLink::Blog]);
        $count = count(SocialLink::cases()) - 1;

        self::assertCount($count, $result1);
        self::assertNotContains(SocialLink::Blog->name, $result1);
        self::assertNotContains(SocialLink::Blog->name, $result2);
    }

    /*
     * Names
     */

    #[Test]
    public function it_returns_names()
    {
        $expected = array_map(fn ($status) => $status->name, Status::cases());
        $names = Status::names();

        self::assertSame($expected, $names);
        self::assertCount(count(Status::cases()), $names);
    }

    #[Test]
    public function it_returns_only_names()
    {
        $result = SocialLink::names(only: SocialLink::Blog);

        self::assertCount(1, $result);
        self::assertContains(SocialLink::Blog->name, $result);
    }

    #[Test]
    public function it_returns_only_names_using_an_array()
    {
        $result = SocialLink::names(only: [SocialLink::Blog, SocialLink::Website]);

        self::assertCount(2, $result);
        self::assertContains(SocialLink::Blog->name, $result);
        self::assertContains(SocialLink::Website->name, $result);
    }

    #[Test]
    public function it_returns_except_names()
    {
        $result = SocialLink::names(except: SocialLink::Blog);
        $count = count(SocialLink::cases()) - 1;

        self::assertCount($count, $result);
        self::assertNotContains(SocialLink::Blog->name, $result);
    }

    #[Test]
    public function it_returns_except_names_using_an_array()
    {
        $result = SocialLink::names(except: [SocialLink::Blog, SocialLink::Website]);
        $count = count(SocialLink::cases()) - 2;

        self::assertCount($count, $result);
        self::assertNotContains(SocialLink::Blog->name, $result);
        self::assertNotContains(SocialLink::Website->name, $result);
    }

    /*
     * Values
     */

    #[Test]
    public function it_returns_values()
    {
        $expected = array_map(fn ($status) => $status->value, Status::cases());
        $values = Status::values();
        $count = count(Status::cases());

        self::assertCount($count, $values);
        self::assertSame($expected, $values);
    }

    #[Test]
    public function it_returns_only_values()
    {
        $result = SocialLink::values(only: SocialLink::Blog);

        self::assertCount(1, $result);
        self::assertContains(SocialLink::Blog->value, $result);
    }

    #[Test]
    public function it_returns_only_values_using_an_array()
    {
        $result = SocialLink::values(only: [SocialLink::Blog, SocialLink::Website]);

        self::assertCount(2, $result);
        self::assertContains(SocialLink::Blog->value, $result);
        self::assertContains(SocialLink::Website->value, $result);
    }

    #[Test]
    public function it_returns_except_values()
    {
        $result = SocialLink::values(except: SocialLink::Blog);
        $count = count(SocialLink::cases()) - 1;

        self::assertCount($count, $result);
        self::assertNotContains(SocialLink::Blog->value, $result);
    }

    #[Test]
    public function it_returns_except_values_using_an_array()
    {
        $result = SocialLink::values(except: [SocialLink::Blog, SocialLink::Website]);
        $count = count(SocialLink::cases()) - 2;

        self::assertCount($count, $result);
        self::assertNotContains(SocialLink::Blog->value, $result);
        self::assertNotContains(SocialLink::Website->value, $result);
    }

    /*
     * Only / Except
     */

    #[Test]
    public function it_returns_only_a_case()
    {
        $result = SocialLink::only(SocialLink::Blog);

        self::assertCount(1, $result);
        self::assertContains(SocialLink::Blog, $result);
    }

    #[Test]
    public function it_returns_only_multiple_cases()
    {
        $result = SocialLink::only(SocialLink::Blog, SocialLink::Website);

        self::assertCount(2, $result);
        self::assertContains(SocialLink::Blog, $result);
        self::assertContains(SocialLink::Website, $result);
    }

    #[Test]
    public function it_returns_only_cases_using_an_array()
    {
        $result = SocialLink::only([SocialLink::Blog, SocialLink::Website]);

        self::assertCount(2, $result);
        self::assertContains(SocialLink::Blog, $result);
        self::assertContains(SocialLink::Website, $result);
    }

    #[Test]
    public function it_returns_except_a_case()
    {
        $result = SocialLink::except(SocialLink::Blog);
        $count = count(SocialLink::cases()) - 1;

        self::assertCount($count, $result);
        self::assertNotContains(SocialLink::Blog, $result);
    }

    #[Test]
    public function it_returns_except_multiple_cases()
    {
        $result = SocialLink::except(SocialLink::Blog, SocialLink::Website);
        $count = count(SocialLink::cases()) - 2;

        self::assertCount($count, $result);
        self::assertNotContains(SocialLink::Blog, $result);
        self::assertNotContains(SocialLink::Website, $result);
    }

    #[Test]
    public function it_returns_except_cases_using_an_array()
    {
        $result = SocialLink::except([SocialLink::Blog, SocialLink::Website]);
        $count = count(SocialLink::cases()) - 2;

        self::assertCount($count, $result);
        self::assertNotContains(SocialLink::Blog, $result);
        self::assertNotContains(SocialLink::Website, $result);
    }

    /*
     * Is / Is Not
     */

    #[Test]
    public function it_checks_enum_is()
    {
        $enum = Status::Draft;

        self::assertTrue($enum->is(Status::Draft));
        self::assertFalse($enum->is(Status::Published));
    }

    #[Test]
    public function it_checks_enum_is_not()
    {
        $enum = Status::Draft;

        self::assertTrue($enum->isNot(Status::Published));
        self::assertFalse($enum->isNot(Status::Draft));
    }

    /*
     * Is Any / Is Not Any
     */

    #[Test]
    public function it_checks_is_any_enum()
    {
        $enum = Status::Draft;

        self::assertTrue($enum->isAny(Status::Draft));
        self::assertFalse($enum->isAny(Status::Published));

        self::assertTrue($enum->isAny(Status::Published, Status::Draft));
        self::assertFalse($enum->isAny(Status::Published, Status::Hidden));
    }

    #[Test]
    public function it_checks_is_any_enum_using_an_array()
    {
        $enum = Status::Draft;

        self::assertTrue($enum->isAny([Status::Published, Status::Draft]));
        self::assertFalse($enum->isAny([Status::Published, Status::Hidden]));
    }

    #[Test]
    public function it_checks_is_not_any_enum()
    {
        $enum = Status::Draft;

        self::assertFalse($enum->isNotAny(Status::Draft));
        self::assertTrue($enum->isNotAny(Status::Published));

        self::assertFalse($enum->isNotAny(Status::Published, Status::Draft));
        self::assertTrue($enum->isNotAny(Status::Published, Status::Hidden));
    }

    #[Test]
    public function it_checks_is_not_any_enum_using_an_array()
    {
        $enum = Status::Draft;

        self::assertFalse($enum->isNotAny([Status::Published, Status::Draft]));
        self::assertTrue($enum->isNotAny([Status::Published, Status::Hidden]));
    }
}
