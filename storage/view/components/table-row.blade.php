@props([
    'columns',
    'row' => null,
])

@php

    /** @var array<string, \App\Shared\Presentation\Grid\Column> $columns */
    /** @var ?object $row */

@endphp

<tr data-id="{!! $row?->id ?? '' !!}">
    @foreach($columns as $field => $col)
        <td data-key="{!! $field !!}"{!! $col->sorted() ? ' class="col-sorted"' : '' !!}>{{ $row->$field ?? '' }}</td>
    @endforeach
    <td class="text-end text-nowrap">
        <button type="button" class="btn btn-sm btn-outline-primary btn-edit" 
                data-bs-toggle="modal" data-bs-target="#m-account-add"
                title="Edit">
            <i class="fa fa-pen"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                data-delete-url="/admin/accounts" data-confirm="Delete this account?"
                title="Delete">
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>