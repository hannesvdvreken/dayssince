<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Days since...</title>
  <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
  <div class="welcome">
    @if ($latest)
      <h2>
        {{{ trans('dayssince.title') }}}
        <a href="{{{ $project['url'] }}}">{{{ $project['name'] }}}</a>:
      </h2>
      <h1 class="{{ HTML::temperature($days) }}">
        <a href="https://{{ Config::get('services.reamaze.brand') }}.reamaze.com/admin/conversations/{{ $latest['slug'] }}">
          {{ $days }}
        </a>
      </h1>
    @else
      <h2>
        {{{ trans('dayssince.no_tickets_title') }}}
        <a href="{{{ $project['url'] }}}">
          {{{ $project['name'] }}}
        </a>
      </h2>
    @endif
  </div>
</body>
</html>
