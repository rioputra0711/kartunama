@props([
    'score' => null,
    'size' => 'md',
])

@php
    $value = $score === null ? null : max(0, min(1, (float) $score));
    $tone = $value === null
        ? 'unknown'
        : ($value >= 0.85 ? 'high' : ($value >= 0.6 ? 'mid' : 'low'));
    $tones = [
        'high' => 'text-verdigris border-verdigris/40 bg-verdigris/10',
        'mid' => 'text-brass-soft border-brass/40 bg-brass/10',
        'low' => 'text-seal-soft border-seal/50 bg-seal/10',
        'unknown' => 'text-ink-400 border-ink-600 bg-ink-800/60',
    ];
    $label = $value === null ? '—' : number_format($value * 100, 0) . '%';
@endphp

<span {{ $attributes->merge([
        'class' => 'tag inline-flex items-center gap-1.5 border rounded-sm px-1.5 py-0.5 tabular-nums ' .
            $tones[$tone] . ($size === 'sm' ? ' text-[10px]' : ''),
    ]) }}
      @if ($value !== null)
          title="Confidence score: {{ $label }}"
      @else
          title="Confidence score belum tersedia"
      @endif>
    @if ($value !== null)
        <span class="inline-block w-1 h-1 rounded-full bg-current"></span>
    @endif
    <span>{{ $label }}</span>
</span>
