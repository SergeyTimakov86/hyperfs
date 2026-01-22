@props([
    'grid',
    'tbody-id'
])

@php

/** @var \App\Shared\Presentation\Grid\Grid $grid */

@endphp


<div class="table-widget">

    {{-- Table --}}
    @if($grid->isEmpty())
        <div class="alert alert-secondary">No Data</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                <tr>
                    @foreach($grid->columns() as $col)
                        @php
                            $label = $col->label();
                            $sorted = $col->sorted();
                        @endphp
                        @if($col->isSortable())
                            <th aria-sort="{!! $col->ariaSort() !!}">
                                <a href="{{ $col->uriQuery() }}">{{ $label }}</a>
                            </th>
                        @else
                            <th>{{ $label }}</th>
                        @endif
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($grid->rows() as $row)
                    <x-table-row :columns="$grid->columns()" :row="$row" />
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>