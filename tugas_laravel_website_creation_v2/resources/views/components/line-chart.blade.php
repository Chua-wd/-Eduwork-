{{--
    Single-series line chart drawn as SVG on the server (no JavaScript library needed).
        <x-line-chart id="clicks" :series="$clickSeries" format="number" label="Product clicks" />
    $series = [['label' => 'Nov 3', 'value' => 120], ...]
    format: "number" or "rupiah"
    Follows the dataviz marks spec: 2px line, ~10% area wash, solid hairline grid,
    end dot with a 2px surface ring, hover crosshair + tooltip, and a table view.
--}}
{{-- partialLast: the last point is a period still in progress (today) -> drawn dashed, hollow dot --}}
@props(['id', 'series', 'format' => 'number', 'label' => 'Value', 'partialLast' => false])

@php
    $w = 720; $h = 240; $padL = 52; $padR = 16; $padT = 14; $padB = 30;
    $values = array_map(fn ($p) => (float) $p['value'], $series);
    $n = count($values);
    $max = max($values ?: [0]) ?: 1;

    // "Nice" y-axis: about 4 round steps (e.g. 0 / 50 / 100 / 150)
    $raw = $max / 4;
    $mag = 10 ** floor(log10($raw));
    $norm = $raw / $mag;
    $step = ($norm <= 1 ? 1 : ($norm <= 2 ? 2 : ($norm <= 2.5 ? 2.5 : ($norm <= 5 ? 5 : 10)))) * $mag;
    $top = $step * ceil($max / $step);

    $plotW = $w - $padL - $padR;
    $plotH = $h - $padT - $padB;
    $xs = []; $ys = [];
    foreach ($values as $i => $v) {
        $xs[] = $padL + ($n > 1 ? $i * $plotW / ($n - 1) : $plotW / 2);
        $ys[] = $padT + $plotH * (1 - $v / $top);
    }

    // Smooth line that never overshoots the data (monotone cubic / Fritsch-Carlson)
    $t = array_fill(0, $n, 0.0);
    $m = [];
    for ($i = 0; $i < $n - 1; $i++) { $m[$i] = ($ys[$i + 1] - $ys[$i]) / ($xs[$i + 1] - $xs[$i]); }
    if ($n > 1) {
        $t[0] = $m[0]; $t[$n - 1] = $m[$n - 2];
        for ($i = 1; $i < $n - 1; $i++) { $t[$i] = ($m[$i - 1] * $m[$i] <= 0) ? 0 : ($m[$i - 1] + $m[$i]) / 2; }
        for ($i = 0; $i < $n - 1; $i++) {
            if ($m[$i] == 0) { $t[$i] = $t[$i + 1] = 0; continue; }
            $a = $t[$i] / $m[$i]; $b = $t[$i + 1] / $m[$i]; $s = $a * $a + $b * $b;
            if ($s > 9) { $tau = 3 / sqrt($s); $t[$i] = $tau * $a * $m[$i]; $t[$i + 1] = $tau * $b * $m[$i]; }
        }
    }
    $f = fn ($v) => round($v, 1);
    $segments = [];
    for ($i = 0; $i < $n - 1; $i++) {
        $dx = ($xs[$i + 1] - $xs[$i]) / 3;
        $segments[] = ' C' . $f($xs[$i] + $dx) . ' ' . $f($ys[$i] + $t[$i] * $dx) . ' '
                    . $f($xs[$i + 1] - $dx) . ' ' . $f($ys[$i + 1] - $t[$i + 1] * $dx) . ' '
                    . $f($xs[$i + 1]) . ' ' . $f($ys[$i + 1]);
    }
    $start = 'M' . $f($xs[0] ?? 0) . ' ' . $f($ys[0] ?? 0);
    $line = $start . implode('', $segments);

    // In-progress last point: solid line up to yesterday, dashed segment into today
    $partial = $partialLast && $n > 1;
    $solid = $partial ? $start . implode('', array_slice($segments, 0, -1)) : $line;
    $dashed = $partial ? 'M' . $f($xs[$n - 2]) . ' ' . $f($ys[$n - 2]) . end($segments) : null;

    $baseY = $padT + $plotH;
    $area = $line . ' L' . $f(end($xs)) . ' ' . $baseY . ' L' . $f($xs[0] ?? 0) . ' ' . $baseY . ' Z';

    $compact = function ($v) use ($format) {
        $s = $v >= 1_000_000 ? rtrim(rtrim(number_format($v / 1_000_000, 1), '0'), '.') . 'M'
           : ($v >= 1_000 ? rtrim(rtrim(number_format($v / 1_000, 1), '0'), '.') . 'K' : (string) (int) $v);
        return $format === 'rupiah' ? 'Rp ' . $s : $s;
    };
    $full = fn ($v) => ($format === 'rupiah' ? 'Rp ' : '') . number_format($v);

    // [label, value] pairs for the tooltip script
    $tooltipPoints = [];
    foreach (array_values($series) as $i => $p) {
        $suffix = ($partial && $i === $n - 1) ? ' · today, so far' : '';
        $tooltipPoints[] = [$p['label'] . $suffix, (float) $p['value']];
    }

    // About 6 x-axis labels, always including the first and last day
    $labelEvery = max(1, (int) ceil(($n - 1) / 5));
@endphp

<div class="line-chart" id="{{ $id }}" tabindex="0" role="group"
     aria-label="{{ $label }}: line chart. Use the left and right arrow keys to read each day."
     data-points='@json($tooltipPoints)'
     data-xs='@json(array_map($f, $xs))' data-ys='@json(array_map($f, $ys))'
     data-format="{{ $format }}" data-width="{{ $w }}">
    <svg viewBox="0 0 {{ $w }} {{ $h }}" role="img" aria-hidden="true">
        <defs>
            <linearGradient id="{{ $id }}-wash" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0" stop-color="var(--chart-1)" stop-opacity=".16"/>
                <stop offset="1" stop-color="var(--chart-1)" stop-opacity="0"/>
            </linearGradient>
        </defs>

        {{-- gridlines + y labels --}}
        @for ($v = 0; $v <= $top + 0.0001; $v += $step)
            @php $gy = $padT + $plotH * (1 - $v / $top); @endphp
            <line x1="{{ $padL }}" x2="{{ $w - $padR }}" y1="{{ $f($gy) }}" y2="{{ $f($gy) }}" class="grid {{ $v == 0 ? 'base' : '' }}"/>
            <text x="{{ $padL - 10 }}" y="{{ $f($gy + 4) }}" text-anchor="end" class="tick">{{ $compact($v) }}</text>
        @endfor

        {{-- x labels --}}
        @foreach ($series as $i => $p)
            @if ($i % $labelEvery === 0 || $i === $n - 1)
                @if ($i === $n - 1 || $n - 1 - $i >= $labelEvery / 2)
                    <text x="{{ $f($xs[$i]) }}" y="{{ $h - 8 }}" text-anchor="{{ $i === 0 ? 'start' : ($i === $n - 1 ? 'end' : 'middle') }}" class="tick">{{ $p['label'] }}</text>
                @endif
            @endif
        @endforeach

        <path d="{{ $area }}" fill="url(#{{ $id }}-wash)"/>
        <path d="{{ $solid }}" fill="none" stroke="var(--chart-1)" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
        @if ($partial)
            <path d="{{ $dashed }}" fill="none" stroke="var(--chart-1)" stroke-width="2" stroke-dasharray="4 4" stroke-linecap="round"/>
        @endif

        {{-- end dot (hollow when the last period is still in progress) --}}
        @if ($n)
            <circle cx="{{ $f(end($xs)) }}" cy="{{ $f(end($ys)) }}" r="4" fill="{{ $partial ? 'var(--surface)' : 'var(--chart-1)' }}"
                    stroke="{{ $partial ? 'var(--chart-1)' : 'var(--surface)' }}" stroke-width="2"/>
        @endif

        {{-- hover layer (moved by the script below) --}}
        <g class="hover" style="display: none;">
            <line class="crosshair" y1="{{ $padT }}" y2="{{ $baseY }}"/>
            <circle class="hover-dot" r="4" fill="var(--chart-1)" stroke="var(--surface)" stroke-width="2"/>
        </g>
        <rect class="hit" x="{{ $padL }}" y="{{ $padT }}" width="{{ $plotW }}" height="{{ $plotH }}" fill="transparent"/>
    </svg>
    <div class="chart-tip" role="status" aria-live="polite" style="display: none;">
        <strong></strong><span></span>
    </div>
</div>

@if ($partialLast)
    <p class="muted" style="font-size: 11px; margin: 6px 0 0; display: flex; align-items: center; gap: 6px;">
        <svg width="18" height="4" aria-hidden="true"><line x1="0" y1="2" x2="18" y2="2" stroke="var(--chart-1)" stroke-width="2" stroke-dasharray="4 4"/></svg>
        Today is still in progress
    </p>
@endif

<details class="chart-table">
    <summary>View as table</summary>
    <table class="table">
        <thead><tr><th>Day</th><th style="text-align: right;">{{ $label }}</th></tr></thead>
        <tbody>
            @foreach (array_reverse($series) as $p)
                <tr><td>{{ $p['label'] }}</td><td class="num" style="text-align: right;">{{ $full($p['value']) }}</td></tr>
            @endforeach
        </tbody>
    </table>
</details>

@once
    <script>
        // Crosshair + tooltip for every .line-chart on the page (mouse and keyboard)
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.line-chart').forEach(function (chart) {
                var points = JSON.parse(chart.dataset.points);
                var xs = JSON.parse(chart.dataset.xs), ys = JSON.parse(chart.dataset.ys);
                var width = +chart.dataset.width, rupiah = chart.dataset.format === 'rupiah';
                var svg = chart.querySelector('svg'), hover = chart.querySelector('.hover');
                var line = chart.querySelector('.crosshair'), dot = chart.querySelector('.hover-dot');
                var tip = chart.querySelector('.chart-tip');
                var current = points.length - 1;

                function show(i) {
                    current = Math.max(0, Math.min(points.length - 1, i));
                    var x = xs[current], y = ys[current];
                    hover.style.display = '';
                    line.setAttribute('x1', x); line.setAttribute('x2', x);
                    dot.setAttribute('cx', x); dot.setAttribute('cy', y);
                    // value first (strong), label second - text set with textContent
                    tip.querySelector('strong').textContent = (rupiah ? 'Rp ' : '') + points[current][1].toLocaleString('en-US');
                    tip.querySelector('span').textContent = points[current][0];
                    tip.style.display = '';
                    var scale = svg.getBoundingClientRect().width / width;
                    var left = x * scale;
                    tip.style.left = Math.min(Math.max(left, 60), svg.getBoundingClientRect().width - 60) + 'px';
                    tip.style.top = Math.max(y * scale - 54, 0) + 'px';
                }
                function hide() { hover.style.display = 'none'; tip.style.display = 'none'; }

                svg.addEventListener('pointermove', function (e) {
                    var r = svg.getBoundingClientRect();
                    var x = (e.clientX - r.left) / r.width * width;
                    var best = 0;
                    for (var i = 1; i < xs.length; i++) { if (Math.abs(xs[i] - x) < Math.abs(xs[best] - x)) best = i; }
                    show(best);
                });
                svg.addEventListener('pointerleave', hide);
                chart.addEventListener('focus', function () { show(current); });
                chart.addEventListener('blur', hide);
                chart.addEventListener('keydown', function (e) {
                    if (e.key === 'ArrowLeft') { show(current - 1); e.preventDefault(); }
                    if (e.key === 'ArrowRight') { show(current + 1); e.preventDefault(); }
                });
            });
        });
    </script>
@endonce
