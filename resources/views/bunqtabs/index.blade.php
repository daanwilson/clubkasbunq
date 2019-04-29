@extends('layouts.right.index')

@section('title','Bunq open betaalverzoeken')
@section('route.new',route('bunqtabs.create'))
@section('label.new','Maak open betaalverzoek aan')

@section('content')
    <div class="table-vue" data-src="{{ $vtable['src'] }}" >
        <input type="search" name="search" class="form-control width-50" placeholder="Search" v-model="table.search.search" @keyup="table.search.doSearch()" />
        @include('layouts.table.pagination',['vtable'=>$vtable])
        <div class="table-responsive">
            <table class="table table-striped table-hover  table-condensed" >
                <thead>
                <tr>
                    <th class="coll-small hidden-xs" @click="table.sorting.doSort('id')" v-bind:class="table.sorting.sorting.id" sortable >#</th>
                    <th class="coll-medium" @click="table.sorting.doSort('AccountId')" v-bind:class="table.sorting.sorting.AccountId" sortable >Account</th>
                    <th class="coll-medium" @click="table.sorting.doSort('amount')" v-bind:class="table.sorting.sorting.amount" sortable >Bedrag</th>
                    <th class="" @click="table.sorting.doSort('description')" v-bind:class="table.sorting.sorting.description" sortable >Omschrijving</th>
                    <th class="" @click="table.sorting.doSort('expires')" v-bind:class="table.sorting.sorting.expires" sortable >Verloopt op</th>
                    @include('layouts.table.tools-header',['vtable'=>$vtable])
                </tr>
                </thead>
                <tbody >
                <tr v-for='row in table.rows' >
                    <td class="coll-small hidden-xs">@{{ row.id }}</td>
                    <td class="coll-small">@{{ row.account.description }} (@{{ row.account.IBAN }})</td>
                    <td class="coll-medium">@{{ row.amount }}</td>
                    <td class="">@{{ row.description }}</td>
                    <td class=""><comp-date v-bind:datetime="row.expires"></comp-date></td>
                    @include('layouts.table.tools',['vtable'=>$vtable])
                </tr>
                </tbody>
            </table>
        </div>
    </div>


@endsection
