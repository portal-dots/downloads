<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PortalDots {{ $major_version }} Releases</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <h1>PortalDots {{ $major_version }} からアップデート可能なリリース</h1>
    <p>下記のバージョンのうち、現在お使いの PortalDots より新しいバージョンにアップデートできます。</p>
    <ul>
        @foreach ($releases as $release)
            <li>
                <a href="{{ route('downloads.zip', ['version' => $release->getVersion()->getFullVersion()]) }}">
                    {{ $release->getVersion()->getFullVersion() }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>
