{{--
    Main admin dashboard (Tugas 18). Data comes from Admin\DashboardController.
    The task asks for: number of products, number of product categories, number of product clicks.
    Those are the first three KPI tiles (marked "Task"); everything else is extra.
--}}
<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    @php
        $local = now('Asia/Jakarta');
        $greeting = $local->hour < 11 ? 'Good morning' : ($local->hour < 15 ? 'Good afternoon' : ($local->hour < 19 ? 'Good evening' : 'Good night'));
        $firstName = \Illuminate\Support\Str::of(Auth::user()->name)->before(' ');
        $goal = 5; // conversion goal in %, shown on the meter
        $meter = min(100, $conversion / $goal * 100);
        $clickDelta = $kpis[2]['delta'];
        $revenueDelta = $kpis[4]['delta'];
        $maxCategoryClicks = max($clicksByCategory->max('total') ?? 0, 1);
        $rp = fn ($v) => 'Rp ' . number_format($v);
    @endphp

    {{-- Header + date range filter (one row, above everything it scopes) --}}
    <div class="dash-top">
        <div>
            <h1 class="display display-lg">{{ $greeting }}, {{ $firstName }}</h1>
            <p class="muted" style="margin: 4px 0 0; font-size: 13px;">
                {{ $local->format('l, j F Y') }} · showing {{ $start->format('M j') }} – {{ $end->format('M j, Y') }}
            </p>
        </div>
        <nav class="segmented" aria-label="Date range">
            @foreach ([7 => 'Last 7 days', 30 => 'Last 30 days', 90 => 'Last 90 days'] as $days => $text)
                <a href="{{ route('dashboard', ['range' => $days]) }}" class="{{ $range === $days ? 'active' : '' }}"
                   @if ($range === $days) aria-current="true" @endif>
                    @if ($range === $days)<x-icon name="check" size="14" />@endif {{ $text }}
                </a>
            @endforeach
        </nav>
    </div>

    {{-- KPI strip + conversion meter --}}
    <section class="kpi-strip" aria-label="Summary">
        @foreach ($kpis as $i => $kpi)
            <div class="kpi">
                <div class="kpi-top" title="{{ $kpi['label'] }}">
                    <span class="kpi-icon"><x-icon :name="$kpi['icon']" size="15" /></span>
                    <span class="kpi-name">{{ $kpi['label'] }}</span>
                </div>
                <div class="kpi-value-row">
                    <div class="kpi-value">{{ $kpi['value'] }}</div>
                    @if ($i < 3)<span class="task-mark" title="Required by the task">Task</span>@endif
                </div>
                @if (array_key_exists('delta', $kpi))
                    @php $d = $kpi['delta']; @endphp
                    @if ($d === null)
                        <span class="delta flat">New</span>
                    @else
                        <span class="delta {{ $d > 0 ? 'up' : ($d < 0 ? 'down' : 'flat') }}">
                            @if ($d > 0)<x-icon name="up" size="11" />@elseif ($d < 0)<x-icon name="down" size="11" />@endif
                            {{ ($d > 0 ? '+' : '') . number_format($d, 1) }}%
                        </span>
                    @endif
                    <span class="kpi-note kpi-sub">vs prev. {{ $range }} days</span>
                @else
                    <span class="kpi-note">{{ $kpi['note'] }}</span>
                @endif
            </div>
        @endforeach

        <div class="meter" aria-label="Click to order conversion {{ number_format($conversion, 1) }} percent, goal {{ $goal }} percent">
            <svg viewBox="0 0 180 100" aria-hidden="true">
                <path d="M14 92 A76 76 0 0 1 166 92" pathLength="100" fill="none" stroke="var(--chart-track)" stroke-width="14" stroke-linecap="round"/>
                <path d="M14 92 A76 76 0 0 1 166 92" pathLength="100" fill="none" stroke="var(--chart-1)" stroke-width="14" stroke-linecap="round"
                      stroke-dasharray="{{ round($meter, 1) }} 100"/>
            </svg>
            <div class="meter-value">{{ number_format($conversion, 1) }}%</div>
            <div class="meter-label">Click → order conversion</div>
            <div class="meter-label" style="margin-top: 6px;">
                Goal {{ $goal }}% · previous period {{ number_format($prevConversion, 1) }}%
            </div>
        </div>
    </section>

    {{-- Row 2: line chart + order fulfillment --}}
    <div class="dash-row" style="grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);">
        <section class="dash-card">
            <div class="card-head">
                <div>
                    <h2 id="trend-title">Product clicks</h2>
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 6px;">
                        <span class="chart-big" id="trend-total">{{ number_format($clicks) }}</span>
                        <span id="trend-delta">
                            @if ($clickDelta !== null)
                                <span class="delta {{ $clickDelta >= 0 ? 'up' : 'down' }}">
                                    <x-icon :name="$clickDelta >= 0 ? 'up' : 'down'" size="11" />{{ ($clickDelta > 0 ? '+' : '') . number_format($clickDelta, 1) }}%
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
                <nav class="segmented" aria-label="Chart metric" style="background: var(--bg);">
                    <a href="#" class="active" data-metric="clicks">Clicks</a>
                    <a href="#" data-metric="revenue">Revenue</a>
                </nav>
            </div>

            <div data-chart="clicks">
                <x-line-chart id="chart-clicks" :series="$clickSeries" format="number" label="Product clicks" :partial-last="true" />
            </div>
            <div data-chart="revenue" style="display: none;">
                <x-line-chart id="chart-revenue" :series="$revenueSeries" format="rupiah" label="Revenue" :partial-last="true" />
            </div>
        </section>

        <section class="dash-card">
            <div class="card-head">
                <div>
                    <h2>Order fulfillment</h2>
                    <p class="muted" style="margin: 2px 0 0; font-size: 12px;">{{ number_format($orderCount) }} orders in this period</p>
                </div>
            </div>
            <div class="hbars">
                @foreach ($fulfillment as $row)
                    <div>
                        <div class="hbar-label">
                            <span>{{ ucfirst($row['status']) }}</span>
                            <span class="muted">{{ number_format($row['count']) }} · {{ round($row['share']) }}%</span>
                        </div>
                        <div class="track" title="{{ ucfirst($row['status']) }}: {{ $row['count'] }} orders ({{ round($row['share']) }}%)">
                            <span style="width: {{ round($row['share'], 1) }}%;"></span>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr class="rule">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <div style="font-size: 20px; font-weight: 600;">{{ $rp($avgOrderValue) }}</div>
                    <div class="muted" style="font-size: 11px;">Avg. order value</div>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: 600;">{{ number_format($itemsPerOrder, 1) }}</div>
                    <div class="muted" style="font-size: 11px;">Items per order</div>
                </div>
            </div>
        </section>
    </div>

    {{-- Row 3: clicks by category + most-clicked products --}}
    <div class="dash-row" style="grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);">
        <section class="dash-card">
            <div class="card-head">
                <div>
                    <h2>Clicks by category</h2>
                    <p class="muted" style="margin: 2px 0 0; font-size: 12px;">Product page visits in this period</p>
                </div>
                <a href="{{ route('dashboard.categories.index') }}">Manage &rarr;</a>
            </div>
            <div class="hbars">
                @forelse ($clicksByCategory as $row)
                    <div>
                        <div class="hbar-label"><span>{{ $row->name }}</span></div>
                        <div class="hbar-row">
                            <div class="hbar" style="width: calc({{ round($row->total / $maxCategoryClicks * 100, 1) }}% - 60px);"
                                 title="{{ $row->name }}: {{ number_format($row->total) }} clicks"></div>
                            <span class="hbar-value">{{ number_format($row->total) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="muted">No clicks in this period.</p>
                @endforelse
            </div>
        </section>

        <section class="dash-card">
            <div class="card-head">
                <div>
                    <h2>Most-clicked products</h2>
                    <p class="muted" style="margin: 2px 0 0; font-size: 12px;">Clicks, orders and revenue in this period</p>
                </div>
                <a href="{{ route('dashboard.products.index') }}">View all &rarr;</a>
            </div>
            @foreach ($topProducts as $product)
                <div class="list-row">
                    <div class="list-thumb"><div class="visual"><x-product-visual :product="$product" /></div></div>
                    <div style="flex: 1; min-width: 0;">
                        <a href="{{ route('dashboard.products.edit', $product) }}" style="text-decoration: none; font-size: 14px; font-weight: 500;">{{ $product->name }}</a>
                        <div class="muted" style="font-size: 12px;">{{ $product->category?->name ?? 'Uncategorized' }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; font-variant-numeric: tabular-nums;">{{ number_format($product->clicks_count) }} clicks</div>
                        <div class="muted" style="font-size: 12px;">{{ $product->orders_count }} orders · {{ $rp($product->revenue ?? 0) }}</div>
                    </div>
                </div>
            @endforeach
        </section>
    </div>

    {{-- Row 4: recent orders --}}
    <section class="dash-card" style="margin-top: 16px;">
        <div class="card-head">
            <h2>Recent orders</h2>
            <span class="muted" style="font-size: 12px;">Latest {{ $recentOrders->count() }}</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order</th><th>Customer</th><th>Product</th><th>Status</th>
                        <th style="text-align: right;">Total</th><th style="text-align: right;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                        @php $initials = collect(explode(' ', $order->user?->name ?? '?'))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode(''); @endphp
                        <tr>
                            <td class="num muted">#NV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <span class="avatar avatar-sm">{{ $initials }}</span>{{ $order->user?->name ?? 'Deleted user' }}
                                </span>
                            </td>
                            <td>{{ $order->product?->name ?? 'Deleted product' }}{{ $order->quantity > 1 ? ' × ' . $order->quantity : '' }}</td>
                            <td><span class="pill-status {{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                            <td class="num" style="text-align: right;">{{ $rp($order->total) }}</td>
                            <td class="num muted" style="text-align: right;">{{ $order->created_at->format('M j') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <script>
        // Switch the trend card between Clicks and Revenue (two separate charts, never two y-axes)
        (function () {
            var data = {
                clicks:  { title: 'Product clicks', total: @json(number_format($clicks)), delta: @json($clickDelta) },
                revenue: { title: 'Revenue', total: @json($rp($revenue)), delta: @json($revenueDelta) }
            };
            document.querySelectorAll('[data-metric]').forEach(function (tab) {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();
                    var metric = tab.dataset.metric, d = data[metric];
                    document.querySelectorAll('[data-metric]').forEach(function (t) { t.classList.toggle('active', t === tab); });
                    document.querySelectorAll('[data-chart]').forEach(function (c) { c.style.display = c.dataset.chart === metric ? '' : 'none'; });
                    document.getElementById('trend-title').textContent = d.title;
                    document.getElementById('trend-total').textContent = d.total;
                    var holder = document.getElementById('trend-delta');
                    holder.textContent = '';
                    if (d.delta !== null) {
                        var chip = document.createElement('span');
                        chip.className = 'delta ' + (d.delta >= 0 ? 'up' : 'down');
                        chip.textContent = (d.delta >= 0 ? '↑ +' : '↓ ') + d.delta.toFixed(1) + '%';
                        holder.appendChild(chip);
                    }
                });
            });
        })();
    </script>
</x-admin-layout>
