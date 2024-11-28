<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Email')</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #ffffff;">

    {{-- Include Header --}}
    @include('email.header')

    {{-- Email Content --}}
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 20px;">
        <tr>
            <td>
                @yield('content')
            </td>
        </tr>
    </table>

    {{-- Include Footer --}}
    @include('email.footer')

</body>
</html>
