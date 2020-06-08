@component('mail::message')

Hi, {{$data['articleUser']}}

# New Comment submitted

# Article:
{{$data['articleName']}}

# Comment:
{{$data['comment']}}

# User:
{{$data['commentUser']}}


Thanks,

Team @article.io
@endcomponent