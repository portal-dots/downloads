<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PortalDots {{ $major_version }} Releases</title>
</head>
<body>
    <h1>PortalDots {{ $major_version }} Releases</h1>
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
