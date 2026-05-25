<?php

declare(strict_types=1);

namespace Flames\Date;

use Flames\Date\Traits\Date;
use DateTimeInterface;
use DateTimeZone;

/**
 * A Carbon-powered immutable DateTime for the Flames framework.
 *
 * Modifiers return new instances instead of mutating the current one.
 *
 * @see \Flames\Date\DateTime for the mutable counterpart.
 */
class DateTimeImmutable extends \DateTimeImmutable implements CarbonInterface
{
    use Date {
        __clone as dateTraitClone;
    }

    // ── Carbon immutable specifics ────────────────────────────────────────

    public function __clone(): void
    {
        $this->dateTraitClone();
        $this->endOfTime   = false;
        $this->startOfTime = false;
    }

    public static function startOfTime(): static
    {
        $date = static::parse('0001-01-01')->years(self::getStartOfTimeYear());
        $date->startOfTime = true;
        return $date;
    }

    public static function endOfTime(): static
    {
        $date = static::parse('9999-12-31 23:59:59.999999')->years(self::getEndOfTimeYear());
        $date->endOfTime = true;
        return $date;
    }

    /** @codeCoverageIgnore */
    private static function getEndOfTimeYear(): int
    {
        return 1118290769066902787;
    }

    /** @codeCoverageIgnore */
    private static function getStartOfTimeYear(): int
    {
        return -1118290769066898816;
    }

    // ── Flames additions ──────────────────────────────────────────────────

    public function __construct(
        self|\Flames\Date\DateTime|\DateTime|\DateTimeInterface|string|int|null $dateTime = 'now',
        TimeZone|DateTimeZone|string|null $timezone = null
    ) {
        if ($timezone === null) {
            $timezone = TimeZone::getDefault();
        }

        if (is_int($dateTime)) {
            parent::__construct('@' . $dateTime);
        } elseif ($dateTime instanceof \DateTimeInterface) {
            parent::__construct($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone());
        } else {
            parent::__construct($dateTime ?? 'now', $timezone);
        }
    }

    public function __toString(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    public function format(string|null $format = null): string
    {
        if ($format === null) {
            $format = 'Y-m-d H:i:s';
        }
        return parent::format($format);
    }

    public static function now(DateTimeZone|string|null $tz = null): static
    {
        return new static('now', $tz);
    }
}
