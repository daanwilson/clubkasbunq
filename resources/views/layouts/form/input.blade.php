<div class="form-group {{@$required && @$errors->has($field) ? 'has-error':''}}">
    <label for="input_{{$field}}" class="col-md-4 control-label">{{$label}}{{ @$required ? '&nbsp;*':'' }}</label>
    <div class="col-md-8">
        @if($type=='textarea')
            @include('layouts.form.textarea',['vue'=>true])
        @elseif($type=='checkbox')
             @include('layouts.form.checkbox',['vue'=>true])
        @elseif($type=='checkboxes')
             @include('layouts.form.checkboxes',['vue'=>true])
        @elseif($type=='password')
             @include('layouts.form.password',['vue'=>true])
        @elseif($type=='file')
             @include('layouts.form.file',['vue'=>true])
        @elseif($type=='select')
             @include('layouts.form.select',['vue'=>true])
        @elseif($type=='multiselect')
             @include('layouts.form.file',['vue'=>true])
        @else
             @include('layouts.form.text',['vue'=>true])
        @endif
        
        @if (@$required)
        <span class="text-danger input-error-icon" v-if="form.errors.has('{{$field}}')" ><span v-text="form.errors.get('{{$field}}')"></span></span>
        @endif
    </div>
</div>