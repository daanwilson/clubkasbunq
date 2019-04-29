@extends('layouts.right.index')

@section('title','Geld posten')
@section('route.new',route('moneyitem.create'))
@section('label.new','Post')

@section('content')
<div class="table-vue" data-src="{{ $vtable['src'] }}" >
    <input type="search" name="search" class="form-control width-50" placeholder="Search" v-model="table.search.search" @keyup="table.search.doSearch()" />
    @include('layouts.table.pagination',['vtable'=>$vtable])
    <div class="table-responsive">
        <table class="table table-striped table-hover table-condensed" >
            <thead> 
                <tr>
                    <th class="coll-small" @click="table.sorting.doSort('id')" v-bind:class="table.sorting.sorting.id" sortable>#</th>
                    <th @click="table.sorting.doSort('item_name')" v-bind:class="table.sorting.sorting.item_name" sortable >Post</th>
                    @include('layouts.table.tools-header',['vtable'=>$vtable])
                </tr>
            </thead>
            <tbody >
                <tr v-for='row in table.rows' >
                    <td class="coll-small">@{{ row.id }}</td>
                    <td>@{{ row.item_name }}</td>
                    @include('layouts.table.tools',['vtable'=>$vtable])
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
