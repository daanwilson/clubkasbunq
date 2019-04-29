@if(@$filter==true)
    <div class="select">
        @endif
        <select name='{{$field}}'
                id="input_{{$field}}"
                class="form-control"
                data-field="{{$field}}"
                @if(@$vue==true)
                v-model="form.data.{{$field}}"
                @change="form.errors.clear('{{$field}}')"
                @endif
                @if(@$filter==true)
                    v-model="table.search.filters.{{$field}}"
                @endif
        >
            @if(!isset($current))
                {{ $current = old($field) ? old($field) : $data->$field }}
            @endif
            @if( @$firstselect )
                <option value=''> - Select -</option>
            @endif
            @foreach($values as $key=>$value)
                @php $keyvalue = (isset($values_key) ? $value->$values_key : $key) @endphp
                @php $value_label = (isset($values_label) ? $value->$values_label : $value) @endphp
                <option value="{{$keyvalue }}"
                        {{ (isset($current) && ($keyvalue==$current || $keyvalue==old($field)) ? 'selected' : '') }}
                >{{ $value_label }}</option>
                <br/>
            @endforeach
        </select>
        @if(@$filter==true)
    </div>
@endif
