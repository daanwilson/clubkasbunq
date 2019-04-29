<div class="form-control relative">
    <input 
        type="{{(@type?$type:'text')}}" 
        name="{{$field}}"     
        value="{{old($field) ? old($field) : $data->$field}}" 
        class="" 
        id="input_{{$field}}" 
        placeholder="{{(@$placeholder?$placeholder:$label)}}" 
        autocomplete="{{ (@autocomplete ? 'on' : 'off') }}"
        @if(@$vue==true)
            v-on:change="form.errors.clear('{{$field}}');form.onFileChange($event);"   
        @endif

        {{ (@$required ? "required" : "") }} 
        {{ (@$autofocus ? "autofocus" : "") }}
        >
        <label class="label label-info absolute right-5 middle"></label>
</div>