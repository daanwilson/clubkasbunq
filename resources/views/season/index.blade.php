@extends('layouts.right.index')

@section('title','Seizoen')
@section('route.new',route('season.create'))
@section('label.new','Seizoen')


@section('content')
<div class="table-vue" data-src="{{ $vtable['src'] }}" >
    <input type="search" name="search" class="form-control width-50" placeholder="Search" v-model="table.search.search" @keyup="table.search.doSearch()" />
    <div class="table-responsive">
        <table class="table table-striped table-hover table-condensed" >
            <thead> 
                <tr>
                    <th class="coll-small" @click="table.sorting.doSort('id')" v-bind:class="table.sorting.sorting.id" sortable >#</th>
                    <th @click="table.sorting.doSort('season_name')" v-bind:class="table.sorting.sorting.season_name" sortable >Name</th>
                    <th @click="table.sorting.doSort('season_start')" v-bind:class="table.sorting.sorting.season_start"  sortable >Start</th>
                    <th @click="table.sorting.doSort('season_end')" v-bind:class="table.sorting.sorting.season_end"  sortable >Stop</th>
                    @include('layouts.table.tools-header',['vtable'=>$vtable])
                </tr>
            </thead>
            <tbody >
                <tr v-for='row in table.rows' >
                    <td class="coll-small">@{{ row.id }}</td>
                    <td>@{{ row.season_name }}</td>
                    <td>@{{ row.season_start }}</td>
                    <td>@{{ row.season_end }}</td>
                    @include('layouts.table.tools',['vtable'=>$vtable])
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
