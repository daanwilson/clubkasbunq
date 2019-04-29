<div id="app-message">
    <div class="status-message" v-if="msg.has()">
        <div class="alert" v-bind:class="['alert-'+msg.getType()]" v-html="msg.getText()">
        </div>
    </div>
</div>

@if (session('status') || session('success'))
<div class="status-message">
    <div class="alert alert-success">
        {{ session('status') }}{{ session('success') }}
    </div>
</div>
@endif
@if (session('info'))
<div class="status-message">
    <div class="alert alert-info">
        {{ session('info') }}
    </div>
</div>
@endif
@if (session('warning'))
<div class="status-message">
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
</div>
@endif
@if (session('danger') || session('error'))
<div class="status-message">
    <div class="alert alert-danger">
        {{ session('danger') }}{{ session('error') }}
    </div>
</div>
@endif
@if (count($errors))
<div class="status-message">
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
</div>
@endif