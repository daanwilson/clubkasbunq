<div class="table-vue" data-src="{{ $vtable['src'] }}" >
    <input type="search" name="search" class="form-control width-50" placeholder="Search" v-model="table.search.search" @keyup="table.search.doSearch()" />
    @include('layouts.table.pagination',['vtable'=>$vtable])
    <div class="table-responsive">
        <table class="table table-striped table-hover  table-condensed" >
            <thead> 
                <tr>
                    <th @click="table.sorting.doSort('created')" v-bind:class="table.sorting.sorting.created" sortable >Datum</th>
                    <th @click="table.sorting.doSort('amount')" v-bind:class="table.sorting.sorting.amount" sortable >Bedrag</th>
                    <th @click="table.sorting.doSort('description')" v-bind:class="table.sorting.sorting.description" sortable >Omschrijving</th>
                    <th @click="table.sorting.doSort('purpose_id')" v-bind:class="table.sorting.sorting.purpose_id" sortable >Doel</th>
                    <th @click="table.sorting.doSort('item_id')" v-bind:class="table.sorting.sorting.item_id" sortable >Post</th>
                    <th>Seizoen</th>

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
                    <td>Total</td>
                    <td><strong><comp-money currency="EUR" v-bind:amount="calcSum('amount')"></comp-money></strong></td>
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
                        <th width="50"></th>
                    @endif
                </tr>
            </tfoot>
            <tbody >
                <tr v-for='row in table.rows' >
                    <td>
                        <div>
                            <comp-date v-bind:datetime="row.created"></comp-date>
                        </div>
                        <div class="text-muted payment-sub-description">
                            <comp-time v-bind:datetime="row.created"></comp-time>
                        </div>
                    </td>
                    <td  v-bind:class="row.amount>0 ? 'text-success' : 'text-danger'"><strong>
                            <comp-money v-bind:currency="row.currency" v-bind:amount="row.amount"></comp-money></strong>
                        <div>
                            <span v-if="row.subType=='PAYMENT'" class='label label-success'>Betaling</span> 
                            <span v-if="row.subType=='REQUEST'" class='label label-info'>Betaalverzoek</span> 
                        </div>
                    </td>
                    <td>
                        <div class="payment-description">@{{ row.description }}</div>
                        <div class="payment-sub-description">
                            <span v-if="row.amount<0">Verzonden aan:</span> 
                            <span v-if="row.amount>=0">Ontvangen van:</span> 
                            @{{ row.counterpart_name }} 
                            @{{ row.counterpart_IBAN }}
                        </div>
                    </td>
                    <td>
                        <select class="form-control input-sm payment-select" v-model='row.purpose_id' @change="table.saveRow(row)">
                            <option value='null'> - select - </option>
                            @foreach($money_purposes as $p)
                            <option value="{{$p->id}}">{{$p->purpose_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><select class="form-control input-sm payment-select" v-model='row.item_id' @change="table.saveRow(row)">
                            <option value="null"> - select - </option>
                            @foreach($money_items as $i)
                            <option value="{{$i->id}}">{{$i->item_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><select class="form-control input-sm payment-select" v-model='row.season_id' @change="table.saveRow(row)">
                            <option value="null"> - select - </option>
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

