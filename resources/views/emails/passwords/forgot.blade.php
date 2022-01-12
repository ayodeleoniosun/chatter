@component('mail::message')
    <p> Hi {{ $first_name }},  </p>
    <p> We received a request to reset the password for your account. </p>
    <p> Click the button below to reset your password </p>

@component('mail::button', ['url' => $url])
    Reset Password
@endcomponent

<p> or copy and paste the URL into your browser <p/>

<a href="{{ $url }}"> {{ $url }} </a>

<p> Ensure that the password is reset within the next ten minutes, else, the link becomes invalid. </p>

Thanks.

@endcomponent

