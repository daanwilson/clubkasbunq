@if($vtable['remove'])
    <td width="50" class="text-danger text-right coll-small" >
        <form method="POST" v-bind:action="table.edit('{{ $vtable['remove_url'] }}/'+row.{{ $vtable['primary_key'] }})" @submit.prevent="onSubmit">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <button title="Delete" type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </form>
    </td>
@endif