@extends('layouts.right.index')

@section('title','Instellingen')
@section('route.new',route('setting.create'))
@section('label.new','Instelling')


@section('content')
<div class="table-vue" data-src="{{ $vtable['src'] }}" >
    <input type="search" name="search" class="form-control width-50" placeholder="Search" v-model="table.search.search" @keyup="table.search.doSearch()" />
    <div class="table-responsive">
        <table class="table table-striped table-hover table-condensed" >
            <thead> 
                <tr>
                    <th class="coll-small" @click="table.sorting.doSort('id')" v-bind:class="table.sorting.sorting.id" sortable >#</th>
                    <th @click="table.sorting.doSort('key')" v-bind:class="table.sorting.sorting.key" sortable >Instelling</th>
                    <th @click="table.sorting.doSort('value')" v-bind:class="table.sorting.sorting.value" sortable >Waarde</th>
                    <th>Info</th>
                    @include('layouts.table.tools-header',['vtable'=>$vtable])
                </tr>
            </thead>
            <tbody >
                <tr v-for='row in table.rows' >
                    <td class="coll-small">@{{ row.id }}</td>
                    <td>@{{ row.key }}</td>
                    <td>@{{ row.value }}</td>
                    <td>@{{ row.info }}</td>
                    @include('layouts.table.tools',['vtable'=>$vtable])
                </tr>
            </tbody>
        </table>      
    </div>
</div>
@endsection
