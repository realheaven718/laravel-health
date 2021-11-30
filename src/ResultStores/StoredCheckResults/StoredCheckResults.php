<?php

namespace Spatie\Health\ResultStores\StoredCheckResults;

use DateTime;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Spatie\Health\Enums\Status;
use function Pest\Laravel\instance;

class StoredCheckResults
{
    public DateTimeInterface $finishedAt;

    /** @var Collection<int, StoredCheckResult> */
    public Collection $storedCheckResults;

    public static function fromJson(string $json): StoredCheckResults
    {
        $properties = json_decode($json, true);

        $checkResults = collect($properties['checkResults'])
            ->map(fn (array $lineProperties) => new StoredCheckResult(...$lineProperties))
            ->sortBy(fn (StoredCheckResult $result) => strtolower($result->label));

        return new self(
            finishedAt: new DateTime($properties['finishedAt']),
            checkResults: $checkResults,
        );
    }

    /**
     * @param \DateTimeInterface|null $finishedAt
     * @param ?Collection<int, StoredCheckResult> $checkResults
     */
    public function __construct(
        DateTimeInterface $finishedAt = null,
        ?Collection       $checkResults = null
    ) {
        $this->finishedAt = $finishedAt ?? new DateTime();

        $this->storedCheckResults = $checkResults ?? collect();
    }

    public function addCheck(StoredCheckResult $line): self
    {
        $this->storedCheckResults[] = $line;

        return $this;
    }

    public function allChecksOk(): bool
    {
        return $this->storedCheckResults->contains(
            fn (StoredCheckResult $line) => $line->status !== Status::ok()->value
        );
    }

    public function containsFailingCheck(): bool
    {
        return ! $this->allChecksOk();
    }

    /**
     * @param array<int, Status>|Status $statuses
     *
     * @return bool
     */
    public function containsCheckWithStatus(array|Status $statuses): bool
    {
        if ($statuses instanceof Status) {
            $statuses = array($statuses);
        }

        return $this->storedCheckResults->contains(
            fn (StoredCheckResult $line) => in_array($line->status, $statuses)
        );
    }

    public function toJson(): string
    {
        return (string)json_encode([
            'finishedAt' => $this->finishedAt->format('Y-m-d H:i:s'),
            'checkResults' => $this->storedCheckResults->map(fn (StoredCheckResult $line) => $line->toArray()),
        ]);
    }
}
