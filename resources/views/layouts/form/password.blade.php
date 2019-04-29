<input type="password" name="{{$field}}_fake" value="" hidden class="hidden" style="display:none;" >
<input type="password" 
       name="{{$field}}" 
       
       value="" 
       class="form-control" 
       id="input_{{$field}}" 
       placeholder="{{(@$placeholder?$placeholder:$label)}}" 
       autocomplete="{{ (@autocomplete ? 'on' : 'off') }}" 
       @if(@$vue==true)
            v-model="form.data.{{$field}}" 
            @keydown="form.errors.clear('{{$field}}')" 
       @endif
       
       {{ (@$required ? 'required' : '') }} 
       {{ (@$autofocus ? 'autofocus' : '') }}
       
       >