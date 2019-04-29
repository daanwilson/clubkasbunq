@extends('layouts.right.index')

@section('title','Teams')
@section('route.new',route('team.create'))
@section('label.new','Team')


@section('content')
<div class="table-vue" data-src="{{ $vtable['src'] }}" >
    <input type="search" name="search" class="form-control width-50" placeholder="Search" v-model="table.search.search" @keyup="table.search.doSearch()" />
    <div class="table-responsive">
        <table class="table table-striped table-hover table-condensed" >
            <thead> 
                <tr>
                    <th @click="table.sorting.doSort('id')" v-bind:class="table.sorting.sorting.id" class="hidden-xs" sortable >#</th>
                    <th @click="table.sorting.doSort('name')" v-bind:class="table.sorting.sorting.name" sortable >Name</th>
                    <th @click="table.sorting.doSort('color')" v-bind:class="table.sorting.sorting.color"  sortable >Color</th>
                    @include('layouts.table.tools-header',['vtable'=>$vtable])
                </tr>
            </thead>
            <tbody >
                <tr v-for='row in table.rows' >
                    <td class="hidden-xs">@{{ row.id }}</td>
                    <td>@{{ row.name }}</td>
                    <td>
                        <div class="color-preview">@{{ row.color }}
                            <div class="color-preview__preview" v-bind:style="{ 'background-color' : row.color }">&nbsp;</div>
                        </div>
                    </td>
                    @include('layouts.table.tools',['vtable'=>$vtable])
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
