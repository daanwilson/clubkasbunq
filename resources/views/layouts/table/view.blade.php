@if($vtable['view'])
    <td width="50" class="text-right coll-small">
        <a class="btn btn-default btn-xs js-btn-edit" title="Bekijken" v-bind:href="table.edit('{{ $vtable['edit_url'] }}/'+row.{{ $vtable['primary_key'] }})">
            <i class="far fa-eye" aria-hidden="true"></i>
        </a>
    </td>
@endif