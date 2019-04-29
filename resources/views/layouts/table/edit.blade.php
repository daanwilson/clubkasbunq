@if($vtable['edit'])
    <td width="50" class="text-right coll-small">
        <a class="btn btn-default btn-xs js-btn-edit" title="Bewerken" v-bind:href="table.edit('{{ $vtable['edit_url'] }}/'+row.{{ $vtable['primary_key'] }})">
            <i class="fa fa-pencil-alt" aria-hidden="true"></i>
        </a>
    </td>
@endif