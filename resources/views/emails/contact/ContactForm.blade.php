@component('mail::message')
# Thanks for contacting us, {{$data['name']}}

<strong>Message:</strong> {{$data['message']}}
<br />
We will get back to you very soon.



Thanks,<br />
Team @article.io
@endcomponent