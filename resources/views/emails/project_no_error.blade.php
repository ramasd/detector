@component('mail::message')
# Introduction

No Error

<p>Project Name: {{ $project->name }}</p>
<br />
<p>Project URL: {{ $project->url }}</p>
<br />
<p>User Name: {{ $project->user->name }}</p>
<br />
<p>Last Check: {{ $project->last_check }}</p>
<br />

@component('mail::button', ['url' => ''])
NO ERROR
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
