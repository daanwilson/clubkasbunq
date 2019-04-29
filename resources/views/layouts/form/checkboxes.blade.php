@foreach($values as $key=>$value)
<label>
    <input 
        type="checkbox" 
        name="{{$field}}[]" 
        value="{{$value->$values_key}}" 
        id="input_{{$field}}_{{$key}}"         
        {{(isset($current) &&  in_array($value->$values_key,$current)) || in_array($value->$values_key,(array)old($field)) ? 'checked' : '' }} 
        
        @if(@$vue==true)
            v-model="form.data.{{$field}}"
            @change="form.errors.clear('{{$field}}')"   
        @endif
        
        >

           {{ $value->$values_label }}
</label>
<br/>
@endforeach
