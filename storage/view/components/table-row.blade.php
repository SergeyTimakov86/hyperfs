@props([
    'columns',
    'row' => null,
])

@php

    /** @var array<string, \App\Shared\Presentation\Grid\Column> $columns */
    /** @var ?object $row */

@endphp

<tr>
    @foreach($columns as $field => $col)
        <td data-key="{!! $field !!}"{!! $col->sorted() ? ' class="col-sorted"' : '' !!}>{{ $row->$field ?? '' }}</td>
    @endforeach
</tr>