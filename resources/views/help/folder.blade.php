<div>

    <style>
        .path .divider{
            font-size: 0.7em;
            vertical-align: 10%;
            color: #AAA;
        }
        .path .divider:last-child{
            display:none;
        }
    </style>

    <h4>Path</h4>
    <ul class="path">
        <li>
            @foreach($breadcrumbs as $name => $p)
                <a href="?path={{ $p }}">{{ $name ? $name : 'Root' }}</a>
                <span class="divider">&#9656;</span>
            @endforeach
        </li>
    </ul>

    <h4>Folders</h4>
    <ul>
        @if($path != '/')
        <li><a href="?path={{ $parent }}">..</a></li>
        @endif
        @foreach($folders as $folder)
        <li><a href="?path={{ $path  . $folder }}">{{ $folder }}</a></li>
        @endforeach
    </ul>

    <h4>Files</h4>
    <ul>
        @foreach($files as $file)
            <li>{{ $file }}</li>
        @endforeach
    </ul>
</div>
