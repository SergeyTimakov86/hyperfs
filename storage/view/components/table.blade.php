@props([
    'grid'
])

@php

/** @var \App\Shared\Presentation\Grid\Grid $grid */

@endphp

<div class="table-widget">

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle {!! $grid->clientSideSort() ? 'sortable' : '' !!}">
            <thead>
            <tr>
                @foreach($grid->columns() as $col)
                    @if($col->isSortable())
                        <th aria-sort="{!! $col->ariaSort() !!}"
                            data-type="{!! $col->type()->value !!}"{!! $col->sorted() ? ' class="col-sorted"' : '' !!}>
                            @if($grid->clientSideSort())
                                <button type="button" class="table-sort-trigger">{{ $col->label() }}</button>
                            @else
                                <a href="{{ $col->uriQuery() }}" class="table-sort-trigger">{{ $col->label() }}</a>
                            @endif
                        </th>
                    @else
                        <th>{{ $col->label() }}</th>
                    @endif
                @endforeach
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @if($grid->isEmpty())
                <tr class="table-empty-row">
                    <td colspan="{{ $grid->columnCount() + 1 }}" class="text-center py-5">
                        <div class="table-empty-state">
                            <div class="mb-2">
                                <i class="fa fa-folder-open fa-3x opacity-25"></i>
                            </div>
                            <div class="h5">No data found</div>
                            <p class="text-secondary mb-0">There are no records to display at the moment.</p>
                        </div>
                    </td>
                </tr>
            @else
                @foreach($grid->rows() as $row)
                    <x-table-row :columns="$grid->columns()" :row="$row"/>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>