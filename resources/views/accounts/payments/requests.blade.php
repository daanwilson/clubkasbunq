<button class="btn btn-primary pull-right" onclick="$('.modal#send_request').modal();">Stuur bestaalverzoek</button>
<div class="table-vue" data-src="{{ $vtable['src'] }}" >
    <input type="search" name="search" class="form-control width-50" placeholder="Search" v-model="table.search.search" @keyup="table.search.doSearch()" />
    @include('layouts.table.pagination',['vtable'=>$vtable])
    <div class="table-responsive">
        <table class="table table-striped table-hover table-condensed" >
            <thead> 
                <tr>
                    <th @click="table.sorting.doSort('created')" v-bind:class="table.sorting.sorting.created" sortable >Datum</th>
                    <th @click="table.sorting.doSort('amount')" v-bind:class="table.sorting.sorting.amount" sortable >Bedrag</th>
                    <th>&nbsp;</th>
                    <th @click="table.sorting.doSort('description')" v-bind:class="table.sorting.sorting.description" sortable >Omschrijving</th>
                    <th @click="table.sorting.doSort('status')" v-bind:class="table.sorting.sorting.status" sortable >Status</th>
                    <th>Link</th>
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
                    <th></th>
                    <th @change="table.search.doFilter($event)">@include('layouts.form.multiselect',['field'=>'status','current'=>[],'firstselect'=>true,'values'=>$statussen,'values_key'=>'status','values_label'=>'status','filter'=>true])</th>
                    <th></th>
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
                <tr v-for='row in table.rows' class="payment-request" v-bind:class="row.status" >
                    <td>
                        <div>
                            <comp-date v-bind:datetime="row.created"></comp-date>
                        </div>
                        <div class="text-muted payment-sub-description">
                            <comp-time v-bind:datetime="row.created"></comp-time>
                        </div>
                    </td>
                    <td  v-bind:class=""><strong>
                            <comp-money v-bind:currency="row.currency" v-bind:amount="row.amount"></comp-money></strong>
                    </td>
                    <td>
                        <i v-if="row.input_type=='request'" class="fa fa-arrow-circle-o-right fa-2x text-success" title="Betaalverzoek verstuurd"></i>
                        <i v-if="row.input_type=='request_response'" class="fa fa-arrow-circle-o-left fa-2x text-danger" title="Betaalverzoek ontvangen"></i>
                        <i v-if="row.status=='ACCEPTED'" class="fa fa-check fa-2x text-success"></i>
                        <i v-if="row.status=='PENDING'" class="fa fa-clock-o fa-2x text-info"></i>
                        <i v-if="row.status=='REVOKED' || row.status=='REJECTED'" class="fa fa-times fa-2x text-danger"></i>
                    </td>
                    
                    <td>
                        <div class="payment-description">@{{ row.description }}</div>
                        <div class="payment-sub-description">
                            <span v-if="row.input_type=='request'">Verzonden aan:</span> 
                            <span v-if="row.input_type=='request_response'">Ontvangen van:</span> 
                            @{{ row.counterpart_name }} 
                            @{{ row.counterpart_IBAN }}
                        </div>
                    </td>
                    <td>
                        <div>@{{ row.status }}</div>
                        <div v-if="row.expire!=null" class="payment-sub-description">Verloopt: <comp-datetime v-bind:datetime="row.expire"></comp-datetime></div>
                    </td>
                    <td>
                        <a v-if="row.share_url!=null" :href="row.share_url" title="Open betaal link." target="_blank"><i class="fa fa-2x fa-qrcode"></i></a>
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
                        <input v-model="form.data.amount" name="amount" type="number" step="0.01" class="form-control" id="amount" required placeholder="1.00" step="1.00" min="0" />
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
