<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Participants - {{ $event->titre }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        h1 { color: #7c3aed; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f3f4f6; }
    </style>
</head>
<body>
<h1>Liste des participants</h1>
<p><strong>Événement :</strong> {{ $event->titre }}</p>
<p><strong>Date :</strong> {{ $event->date->format('d/m/Y H:i') }}</p>
<p><strong>Total :</strong> {{ $participants->count() }} participant(s)</p>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Téléphone</th>
    </tr>
    </thead>
    <tbody>
    @foreach($participants as $p)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $p->prenom }} {{ $p->nom }}</td>
            <td>{{ $p->email }}</td>
            <td>{{ $p->telephone ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
