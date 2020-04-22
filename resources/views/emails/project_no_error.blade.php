@component('mail::message')
# Introduction

<p>No Error</p>

<p><b>Project Name</b>: {{ $project->name }}</p>
<br />
<p><b>Project URL</b>: {{ $project->url }}</p>
<br />
<p><b>User Name</b>: {{ $project->user->name }}</p>
<br />
<p><b>Last Check</b>: {{ $project->last_check }}</p>
<br />
<p><b>Status</b>: {{ $request_data['status'] ?? "" }}</p>
<br />

@component('mail::button', ['url' => ''])
NO ERROR
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
