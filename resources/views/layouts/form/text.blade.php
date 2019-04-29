<input 
    type="{{(@type?$type:'text')}}" 
    name="{{$field}}"     
    value="{{old($field) ? old($field) : $data->$field}}" 
    class="form-control" 
    id="input_{{$field}}" 
    placeholder="{{(@$placeholder?$placeholder:$label)}}" 
    autocomplete="{{ (@autocomplete ? 'on' : 'off') }}"
    @if(@$max!==null)
        max="{{@$max}}"
    @endif
    @if(@$min!==null)
        min="{{@$min}}"
    @endif
    @if(@$vue==true)
        v-model="form.data.{{$field}}"
        @keydown="form.errors.clear('{{$field}}')"
    @endif
   
    {{ (@$required ? "required" : "") }} 
    {{ (@$autofocus ? "autofocus" : "") }}
 >