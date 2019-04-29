<div class="checkbox">    
    <input type="hidden" hidden name="{{$field}}" value=""> <?php //Deze is om ervoor te zorgen dat een 'unchecked' checkbox, toch gepost wordt. ?>
    <label>
        <input 
            type="checkbox" 
            name="{{$field}}" 
            id="input_{{$field}}"
            value="on"
            {{ old($field) ? (old($field) == 'on' ? 'checked' : '') : ($data->$field == 'on' ? 'checked' : '') }}
            @if(@$vue==true)
                v-model="form.data.{{$field}}"
                @keydown="form.errors.clear('{{$field}}')"
            @endif 
            >{{ $info }}
    </label>
</div>
