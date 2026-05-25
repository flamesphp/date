<?php

declare(strict_types=1);

namespace Flames\Date;

use Flames\Date\Traits\Date;
use DateTimeInterface;
use DateTimeZone;

/**
 * A Carbon-powered DateTime for the Flames framework.
 *
 * This class merges Carbon's full date/time API with Flames-specific
 * conveniences. All Carbon static factories (::now(), ::parse(), ::create()…)
 * and chainable mutators are available directly on this class.
 *
 * @see \Flames\Date\DateTimeImmutable for the immutable counterpart.
 */
class DateTime extends \DateTime implements CarbonInterface
{
    use Date;

    // ── Carbon compatibility ──────────────────────────────────────────────

    /**
     * Returns true because this is the mutable variant.
     */
    public static function isMutable(): bool
    {
        return true;
    }

    // ── Flames additions ──────────────────────────────────────────────────

    public function __construct(
        self|\Flames\Date\DateTimeImmutable|\DateTime|\DateTimeInterface|string|int|null $dateTime = 'now',
        TimeZone|DateTimeZone|string|null $timezone = null
    ) {
        if (is_int($dateTime)) {
            parent::__construct('@' . $dateTime);
            if ($timezone !== null) {
                $this->setTimezone($timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone));
            }
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
