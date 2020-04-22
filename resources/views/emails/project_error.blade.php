@component('mail::message')
# Introduction

<p>Project Error</p>

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
<p><b>Error</b>: {{ $request_data['error_message'] ?? "" }}</p>
<br />


@component('mail::button', ['url' => ''])
ERROR
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
