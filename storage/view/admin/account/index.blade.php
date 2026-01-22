@php

/** @var array<string, \App\Domain\Model\Game> $games */

$modalId = 'm-account-add';
$modalLabelId = 'l-account-add';

@endphp

@extends('layouts.admin')
@section('content')
    <div class="row">

        {{-- Sidebar --}}
        <aside class="col-md-3 col-lg-2 sidebar py-3">
            <div class="list-group">
                @foreach ($games as $slug => $game)
                    @php
                        $active = $gameSlug === $slug;
                    @endphp

                    <a class="list-group-item list-group-item-action {{ $active ? 'active' : '' }}"
                       href="/admin/account?gameSlug={{ $slug }}"
                    >
                        {{ $game->title() }}
                    </a>

                @endforeach
            </div>
        </aside>

        {{-- Main content --}}
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4>Accounts</h4>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#{!! $modalId !!}">
                    + Add
                </button>
            </div>

            <x-table :grid="$grid" id="accountsTable" />

        </main>

    </div>

    <template id="row-template">
        <x-table-row :columns="$grid->columns()" />
    </template>

    <div class="modal fade" id="{!! $modalId !!}" tabindex="-1" aria-labelledby="{!! $modalLabelId !!}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="{!! $modalLabelId !!}">Add Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form class="ajax-form-add" method="POST" action="/admin/account">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $gameSlug ? $games[$gameSlug]->id() : '' }}">

                        <div class="mb-3">
                            <label class="form-label">FunPay Name</label>
                            <input type="text" class="form-control" name="funpay_name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ingame ID</label>
                            <input type="text" class="form-control" name="ingame_id">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ingame Name</label>
                            <input type="text" class="form-control" name="ingame_name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Corporation</label>
                            <input type="text" class="form-control" name="ingame_corp">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alliance</label>
                            <input type="text" class="form-control" name="ingame_alliance">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_seller">
                            <label class="form-check-label">Is Seller</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discord</label>
                            <input type="text" class="form-control" name="discord">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telegram</label>
                            <input type="text" class="form-control" name="telegram">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Account</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection