<div class="multiselect">
    <select
        id="input_{{$field}}" 
        class="form-control" 
        data-field="{{$field}}"
        >
        <option value=''> - Select {{ (isset($current) && is_array($current) ? '('.count($current).')' : '') }} - </option>
    </select>
    <div class="multiselect__container">
        <div class="multiselect__option">
            <button type='button' class='multiselect__btn-toggle' @click="table.search.toggleFilters($event)">Toggle all</button>
        </div>
        @foreach($values as $key=>$value)
            @php $key_value = (isset($values_key) ? $value->$values_key : $key) @endphp
            @php $value_label = (isset($values_label) ? $value->$values_label : $value) @endphp
        <div class="multiselect__option">
                <input type="checkbox" name="{{$field}}[]"
                       value="{{$key_value}}"
                       id="input_{{$field}}_{{$key}}" 
                        @if(@$vue==true)
                            v-model="form.data.{{$field}}.{{$key}}"
                            @change="form.errors.clear('{{$field}}')"  
                        @endif
                        @if(@$filter==true)
                            v-model="table.search.filters.{{$field}}"                            
                        @endif
                       {{(isset($current) && in_array($key_value,$current)) || in_array($key_value,(array)old($field)) ? 'checked' : '' }}
                       >

            <label for="input_{{$field}}_{{$key}}" title="{{ $value_label }}">{{ $value_label }}</label>
        </div>
        @endforeach
    </div>
</div>

