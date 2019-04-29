<textarea  
    name="{{$field}}" 
    class="form-control" 
    id="input_{{$field}}" 
    placeholder="{{(@$placeholder?$placeholder:$label)}}" 
    autocomplete="{{ (@autocomplete ? 'on' : 'off') }}" 
    @if(@$vue==true)
        v-model="form.data.{{$field}}"
        @keydown="form.errors.clear('{{$field}}')"  
    @endif
    {{ (@$required ? 'required' : '') }} 
    {{ (@$autofocus ? 'autofocus' : '') }} >{{old($field) ? old($field) : $data->$field}}
</textarea>