<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f2f5;
    -webkit-font-smoothing: antialiased;
}

.wrapper {
    width: 100%;
    table-layout: fixed;
    background-color: #f0f2f5;
    padding: 30px 0;
}

.content {
    max-width: 600px;
}

.inner-body {
    background-color: #ffffff;
    border-radius: 16px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
}

.content-cell {
    padding: 32px 40px 28px;
}

.header-cell {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    text-align: center;
    padding: 36px 20px 28px;
    border-radius: 16px 16px 0 0 !important;
}
.header-cell img {
    max-width: 160px;
    height: auto;
}

.footer-cell {
    text-align: center;
    padding: 20px 0 28px;
    color: #94a3b8;
    font-size: 13px;
    line-height: 1.5;
}

.button {
    display: inline-block;
    padding: 14px 36px;
    font-size: 15px;
    font-weight: 600;
    line-height: 1.4;
    text-align: center;
    text-decoration: none;
    border-radius: 50px !important;
    transition: none;
    box-sizing: border-box;
}
.button-primary, .button-success {
    background-color: #059669 !important;
    color: #ffffff !important;
    border: 1px solid #059669 !important;
}
.button-error {
    background-color: #dc2626 !important;
    color: #ffffff !important;
    border: 1px solid #dc2626 !important;
}

h1 {
    font-size: 22px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px;
    line-height: 1.3;
}
p {
    font-size: 15px;
    color: #475569;
    line-height: 1.6;
    margin: 0 0 16px;
}

.subcopy {
    border-top: 1px solid #e2e8f0;
    padding-top: 20px;
    margin-top: 28px;
    font-size: 13px;
    color: #94a3b8;
    line-height: 1.5;
}
.subcopy a {
    color: #059669;
    word-break: break-all;
}

@media only screen and (max-width: 600px) {
    .inner-body { width: 100% !important; }
    .content-cell { padding: 24px 20px 20px !important; }
    .button { width: 100% !important; }
}
</style>
{!! $head ?? '' !!}
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
{!! $header ?? '' !!}

<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important; padding: 0 16px;">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell">
{!! Illuminate\Mail\Markdown::parse($slot) !!}

{!! $subcopy ?? '' !!}
</td>
</tr>
</table>
</td>
</tr>

{!! $footer ?? '' !!}
</table>
</td>
</tr>
</table>
</body>
</html>
