@extends('layouts.right.index')

@section('title','Leden')
@section('route.new',route('members.import'))
@section('label.new','Import leden')

@section('content')
<div class="table-vue" data-src="{{ $vtable['src'] }}" >
    <input type="search" name="search" class="form-control width-50" placeholder="Search" v-model="table.search.search" @keyup="table.search.doSearch()" />
    @include('layouts.table.pagination',['vtable'=>$vtable])
    <div class="table-responsive">
        <table class="table table-striped table-hover  table-condensed" >
            <thead> 
                <tr>
                    <th class="coll-small hidden-xs" @click="table.sorting.doSort('id')" v-bind:class="table.sorting.sorting.id" sortable >#</th>
                    <th class="coll-small"><input type="checkbox" @change="table.selection.toggleSelect($event)"></th>
                    <th class="hidden-xs" @click="table.sorting.doSort('member_id')" v-bind:class="table.sorting.sorting.member_id" sortable >Lidnr.</th>
                    <th @click="table.sorting.doSort('member_firstname')" v-bind:class="table.sorting.sorting.member_firstname"  sortable >Voornaam</th>
                    <th @click="table.sorting.doSort('member_lastname')" v-bind:class="table.sorting.sorting.member_lastname"  sortable >Achternaam</th>
                    <th class="hidden-xs" @click="table.sorting.doSort('member_birthdate')" v-bind:class="table.sorting.sorting.member_birthdate"  sortable >Leeftijd</th>
                    <th width="150">Speltakken</th>
                    <th class="hidden-xs" width="150">Functies</th>
                    <th class="hidden-xs" @click="table.sorting.doSort('clubloten')" v-bind:class="table.sorting.sorting.clubloten" sortable width="50">Clubloten</th>
                    <th width="150">Betaalverzoeken</th>
                    @include('layouts.table.tools-header',['vtable'=>$vtable])
                </tr>
                <tr>
                    <th class="coll-small hidden-xs"></th>
                    <th class="coll-small"></th>
                    <th class="hidden-xs"></th>
                    <th></th>
                    <th></th>
                    <th class="hidden-xs"></th>
                    <th @change="table.search.doFilter($event)">@include('layouts.form.multiselect',['field'=>'Teams','current'=>$userteams,'firstselect'=>true,'values'=>$teams,'values_key'=>'id','values_label'=>'name','filter'=>true])</th>
                    <th class="hidden-xs" @change="table.search.doFilter($event)">@include('layouts.form.multiselect',['field'=>'MemberRoles','current'=>[],'firstselect'=>true,'values'=>$roles,'values_key'=>'id','values_label'=>'role_name','filter'=>true])</th>
                    <th class="hidden-xs" @change="table.search.doFilter($event)">@include('layouts.form.select',['field'=>'clubloten','current'=>[],'firstselect'=>true,'values'=>[0=>'Geen loten verkocht',1=>'Minimaal 1 lot verkocht',4=>'Minder dan 5 loten verkocht',20=>'Meer dan 20 loten verkocht'],'filter'=>true])</th>
                    <th></th>
                    @if($vtable['view'])
                        <th width="50" class="coll-small"></th>
                    @endif
                    @if($vtable['edit'])
                        <th width="50" class="coll-small"></th>
                    @endif
                    @if($vtable['remove'])
                        <th width="50" class="coll-small"></th>
                    @endif
                </tr>
            </thead>
            <tfoot>
            <td></td>
            @if(Auth::user()->can('account-payment-requests'))
            <td colspan="100%">
                <i class="fa fa-level-up mirror-horizontal"></i>
                <select name="action" class="form-control form-control-inline input-sm">
                    <option value=""> - selecteer actie - </option>
                    <option value="send_request">Stuur betaalverzoek</option>
                </select>
                <button type="button" name="do_action" value="true" class="btn btn-success btn-sm" @click="table.selection.doAction($event)">OK</button>
            </td>
                @endif
            </tfoot>
            <tbody >
                <tr v-for='row in table.rows' >
                    <td class="coll-small hidden-xs">@{{ row.id }}</td>
                    <td class="coll-small"><input type="checkbox" name="member[]" :value="row.id" v-model="table.selection.selected" ></td>
                    <td class="hidden-xs">@{{ row.member_id }}</td>
                    <td>@{{ row.member_firstname }}</td>
                    <td>@{{ row.member_insertion }} @{{ row.member_lastname }}</td>
                    <td class="hidden-xs">@{{ row.member_age }}</td>
                    <td>
                        <ul class='list-unstyled'>
                            <li v-for="team in row.teams" class="ellipsis">
                                @{{ team.name }}
                            </li>
                        </ul>
                    </td>
                    <td class="hidden-xs">
                        <ul class='list-unstyled'>
                            <li v-for="role in row.member_roles">
                                @{{ role.role_name }}
                            </li>
                        </ul>
                    </td>
                    <td class="text-center hidden-xs">

                        <span v-html="row.clubloten"></span>
                    </td>
                    <td>
                        <span v-for="pr in row.payment_requests">
                            <label class="label label-default" v-if="pr.status=='OPEN'" v-bind:title="pr.description+' (OPEN)'">
                                <i class="fas fa-euro-sign"></i>
                            </label>
                            <label class="label label-success" v-if="pr.status=='ACCEPTED'" v-bind:title="pr.description+' (SUCCESS)'">
                                <i class="fas fa-euro-sign"></i>
                            </label>
                            <label class="label label-danger" v-if="pr.status=='EXPIRED'" v-bind:title="pr.description+' (VERLOPEN)'">
                                <i class="fas fa-euro-sign"></i>
                            </label>
                        </span>
                    </td>

                    @include('layouts.table.tools',['vtable'=>$vtable])
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="send_request" tabindex="-1" role="dialog" aria-labelledby="send_request">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route("members.action") }}"  class="form-vue" @submit.prevent="onSubmit">
                {!! csrf_field() !!}
                <input v-model="form.data.ids" type="hidden" name="ids"/>
                <input v-model="form.data.action" type="hidden" name="action"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Stuur betaalverzoek</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="bankaccount" class="control-label">Bankrekening:</label>
                        <select v-model="form.data.bankaccount" name="bankaccount" id="bankaccount" class="form-control" required >
                            <option value=""> - select - </option>
                            @foreach($bankaccounts as $bankaccount)
                                <option value="{{ $bankaccount->id }}" {{ @(count($bankaccounts)==1?'selected':'') }} >{{ $bankaccount->description }} - {{ getChunked($bankaccount->IBAN,4) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Omschrijving:</label>
                        <input v-model="form.data.description" name="description" type="text" class="form-control" id="description" required placeholder="Bijv: Zomerkamp,Weekendje of Contributie">
                    </div>
                    <div class="form-group">
                        <label for="amount" class="control-label">Bedrag:</label>
                        <input v-model="form.data.amount" name="amount" type="text" class="form-control js-input-number" id="amount" required placeholder="10.00" />
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
