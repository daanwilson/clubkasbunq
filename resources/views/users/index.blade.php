@extends('layouts.right.index')

@section('title','Gebruikers')
@section('route.new',route('user.create'))
@section('label.new','Gebruiker')

@section('left')
@include('users.menu')
@endsection

@section('content')
<div class="table-vue" data-src="{{ $vtable['src'] }}" >
    <input type="search" name="search" class="form-control width-50" placeholder="Search" v-model="table.search.search" @keyup="table.search.doSearch()" />
    <div class="table-responsive">
        <table class="table table-striped table-hover table-condensed" >
            <thead> 
                <tr>
                    <th class="coll-small" @click="table.sorting.doSort('id')" v-bind:class="table.sorting.sorting.id" sortable >#</th>
                    <th @click="table.sorting.doSort('name')" v-bind:class="table.sorting.sorting.name" sortable >Name</th>
                    <th @click="table.sorting.doSort('email')" v-bind:class="table.sorting.sorting.email"  sortable >Email</th>
                    @include('layouts.table.tools-header',['vtable'=>$vtable])
                </tr>
            </thead>
            <tbody >
                <tr v-for='row in table.rows' >
                    <td class="coll-small">@{{ row.id }}</td>
                    <td>@{{ row.name }}</td>
                    <td>@{{ row.email }}</td>
                    @include('layouts.table.tools',['vtable'=>$vtable])
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
