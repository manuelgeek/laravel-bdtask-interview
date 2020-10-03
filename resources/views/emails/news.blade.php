@component('mail::message')
# New Posts from {{ env('APP_NAME') }}

@foreach($posts as $post)
## {{ $post->title  }}

### Post:

{{(strlen($post->body) > 600) ? substr($post->body,0,600).'...' : $post->body }}

@component('mail::button', ['url' => url('/')])
    View Post
@endcomponent


@endforeach


Thanks ,<br>
{{ config('app.name') }}
@endcomponent
