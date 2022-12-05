<?php

namespace App\SparkLine;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

final class SparkLine
{
    private int $maxValue;

    public static function new(Collection $days): self
    {
        return new self($days);
    }

    public function __construct(
        private Collection $days,
        private int $maxItemAmount = 20,
        private int $width = 150,
        private int $height = 25,
        private array $colors = ['#c82161', '#fe2977', '#b848f5', '#b848f5'],
        ?int $maxValue = null,
    ) {
        $this->maxValue = $maxValue ?? $this->resolveMaxValueFromDays();
        $this->days = $this->days->mapWithKeys(
            fn (SparkLineDay $day) => [$day->day->format('Y-m-d') => $day]
        );
    }

    public function getTotal(): int
    {
        return $this->days->sum(fn (SparkLineDay $day) => $day->visits) ?? 0;
    }

    public function withMaxValue(?int $maxValue): self
    {
        $clone = clone $this;

        $clone->maxValue = $maxValue ?? $clone->resolveMaxValueFromDays();

        return $clone;
    }

    public function withColors(string ...$colors): self
    {
        $clone = clone $this;

        $clone->colors = $colors;

        return $clone;
    }

    public function make(): string
    {
        return view('visitsSvg', [
            'coordinates' => $this->resolveCoordinated(),
            'colors' => $this->resolveColors(),
            'width' => $this->width,
            'height' => $this->height,
            'id' => Uuid::uuid4()->toString(),
        ])->render();
    }

    public function __toString(): string
    {
        return $this->make();
    }

    private function resolveColors(): array
    {
        $percentageStep = floor(100 / count($this->colors));

        $colorsWithPercentage = [];

        foreach ($this->colors as $i => $color) {
            $colorsWithPercentage[$i * $percentageStep] = $color;
        }

        return $colorsWithPercentage;
    }

    private function resolveMaxValueFromDays(): int
    {
        if ($this->days->isEmpty()) {
            return 0;
        }

        return $this->days
            ->sortByDesc(fn (SparkLineDay $day) => $day->visits)
            ->first()
            ->visits + 1;
    }

    private function resolveCoordinated(): string
    {
        $step = floor($this->width / $this->maxItemAmount);

        return collect(range(0, $this->maxItemAmount))
            ->map(fn (int $days) => now()->subDays($days)->format('Y-m-d'))
            ->reverse()
            ->mapWithKeys(function (string $key) {
                /** @var SparkLineDay|null $day */
                $day = $this->days[$key] ?? null;

                return [
                    $key => $day
                        ? $day->rebase($this->height, $this->maxValue)->visits
                        : 1, // Default value is 1 because 0 renders too small a line
                ];
            })
            ->values()
            ->map(fn (int $visits, int $index) => $index * $step . ',' . $visits)
            ->implode(' ');
    }
}
