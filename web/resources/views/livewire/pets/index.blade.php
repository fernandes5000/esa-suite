<h1>Your Pets</h1>

<a href="/pets/create">Add New Pet</a>

<ul>
    @foreach($pets as $pet)
        <li>
            {{ $pet['name'] }} ({{ $pet['type'] }})
        </li>
    @endforeach
</ul>
