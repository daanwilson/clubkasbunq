@extends('layouts.right.edit')

@section('title','Kleine kas beheren')
@section('route.back',route('cash.index'))

@section('content')


    <div class="table-vue" data-src="{{ $vtable['src'] }}">
        <input type="search" name="search" class="form-control width-50" placeholder="Search"
               v-model="table.search.search" @keyup="table.search.doSearch()"/>
        @include('layouts.table.pagination',['vtable'=>$vtable])
        <div class="table-responsive">
            <table class="table table-striped table-hover  table-condensed">
                <thead>
                <tr>
                    <th @click="table.sorting.doSort('created')" v-bind:class="table.sorting.sorting.created" sortable
                        width="150">Datum
                    </th>
                    <th @click="table.sorting.doSort('amount')" v-bind:class="table.sorting.sorting.amount" sortable
                        width="100">Bedrag
                    </th>
                    <th @click="table.sorting.doSort('description')" v-bind:class="table.sorting.sorting.description"
                        sortable>Omschrijving
                    </th>
                    <th @click="table.sorting.doSort('purpose_id')" v-bind:class="table.sorting.sorting.purpose_id"
                        sortable width="150">Doel
                    </th>
                    <th @click="table.sorting.doSort('item_id')" v-bind:class="table.sorting.sorting.item_id" sortable
                        width="150">Post
                    </th>
                    <th width="150">Seizoen</th>

                    @if($vtable['view'])
                        <th width="50">View</th>
                    @endif
                    @if($vtable['edit'])
                        <th width="50">Edit</th>
                    @endif
                    @if($vtable['remove'])
                        <th width="50">Delete</th>
                    @endif
                </tr>
                <tr v-bind:class="Object.keys(table.search.filters).length > 0 ? 'danger' : 'info'">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th @change="table.search.doFilter($event)">@include('layouts.form.multiselect',['field'=>'purpose_id','current'=>[],'firstselect'=>true,'values'=>$money_purposes,'values_key'=>'id','values_label'=>'purpose_name','filter'=>true])</th>
                    <th @change="table.search.doFilter($event)">@include('layouts.form.multiselect',['field'=>'item_id','current'=>[],'firstselect'=>true,'values'=>$money_items,'values_key'=>'id','values_label'=>'item_name','filter'=>true])</th>
                    <th @change="table.search.doFilter($event)">@include('layouts.form.multiselect',['field'=>'season_id','current'=>[$currentseason_id],'firstselect'=>true,'values'=>$seasons,'values_key'=>'id','values_label'=>'season_name','filter'=>true])</th>
                    @if($vtable['view'])
                        <th width="50"></th>
                    @endif
                    @if($vtable['edit'])
                        <th width="50"></th>
                    @endif
                    @if($vtable['remove'])
                        <th width="50"></th>
                    @endif
                </tr>
                </thead>
                <tfoot>

                <tr class='info'>
                    <td>Totaal</td>
                    <td><strong>
                            <comp-money currency="EUR" v-bind:amount="calcSum('amount')"></comp-money>
                        </strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @if($vtable['view'])
                        <th width="50"></th>
                    @endif
                    @if($vtable['edit'])
                        <th width="50"></th>
                    @endif
                    @if($vtable['remove'])
                        <th width="50" class="text-right">
                            <button title="Add" type="submit" class="btn btn-success btn-xs" onclick="$('.modal#add_cash').modal();">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </th>
                    @endif
                </tr>
                </tfoot>
                <tbody>
                <tr v-for='row in table.rows'>
                    <td>
                        <input class="form-control input-sm" v-model="row.date" @change="table.saveRow(row)"
                               type="date"/>
                    </td>
                    <td>
                        <input class="form-control input-sm js-input-number" v-bind:style="row.amount>0 ? 'color:green' : 'color:red'"
                               v-model="row.amount" @change="table.saveRow(row)" type="text" />
                    </td>
                    <td>
                        <input class="form-control input-sm" v-model="row.description" @change="table.saveRow(row)"
                               type="text"/>
                    </td>
                    <td>
                        <select class="form-control input-sm payment-select" v-model='row.purpose_id'
                                @change="table.saveRow(row)">
                            <option value='null'> - select -</option>
                            @foreach($money_purposes as $p)
                                <option value="{{$p->id}}">{{$p->purpose_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><select class="form-control input-sm payment-select" v-model='row.item_id'
                                @change="table.saveRow(row)">
                            <option value="null"> - select -</option>
                            @foreach($money_items as $i)
                                <option value="{{$i->id}}">{{$i->item_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><select class="form-control input-sm payment-select" v-model='row.season_id'
                                @change="table.saveRow(row)">
                            <option value="null"> - select -</option>
                            @foreach($seasons as $i)
                                <option value="{{$i->id}}">{{$i->season_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    @include('layouts.table.view',['vtable'=>$vtable])
                    @include('layouts.table.edit',['vtable'=>$vtable])
                    @include('layouts.table.remove',['vtable'=>$vtable])
                </tr>
                </tbody>
            </table>
        </div>
    </div>




    <!-- Modal -->
    <div class="modal fade" id="add_cash" tabindex="-1" role="dialog" aria-labelledby="add_cash">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route("cash.store",$team->id) }}"  class="form-vue" @submit.prevent="onSubmit">
                    {!! csrf_field() !!}
                    <input type="hidden" name="season_id" v-model="form.data.season_id" value="{{$currentseason_id}}" id="season_id" />
                    <input type="hidden" name="team_id" v-model="form.data.team_id" value="{{$team->id}}" id="team_id" />
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Voeg regel toe</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="date" class="control-label">Datum:</label>
                            <input v-model="form.data.date" name="date" type="date" class="form-control" id="date" required value="{{date('Y-m-d')}}">
                        </div>

                        <div class="form-group">
                            <label for="amount" class="control-label">Bedrag:</label>
                            <input v-model="form.data.amount" name="amount" type="text" class="form-control js-input-number" id="amount" required placeholder="1.00" />
                        </div>

                        <div class="form-group">
                            <label for="description" class="control-label">Omschrijving:</label>
                            <input v-model="form.data.description" name="description" type="text" class="form-control" id="description" required placeholder="Omschrijving van de betaling">
                        </div>

                        <div class="form-group">
                            <label for="doel" class="control-label">Doel:</label>
                            <select class="form-control" v-model="form.data.purpose_id" id="purpose_id" name="doel" required>
                                <option value=''> - select -</option>
                                @foreach($money_purposes as $p)
                                    <option value="{{$p->id}}">{{$p->purpose_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="post" class="control-label">Post:</label>
                            <select class="form-control" v-model="form.data.item_id" id="purpose_id" name="post" required>
                                <option value=''> - select -</option>
                                @foreach($money_items as $p)
                                    <option value="{{$p->id}}">{{$p->item_name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Stuur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

