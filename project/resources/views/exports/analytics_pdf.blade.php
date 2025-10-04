<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analyses Eventia</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        h1 { color: #7c3aed; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f3f4f6; }
    </style>
</head>
<body>
<h1>Analyses Eventia</h1>
<p>Date : {{ now()->format('d/m/Y') }}</p>

<h2>Statistiques générales</h2>
<ul>
    <li>Utilisateurs inscrits : {{ $totalUsers }}</li>
    <li>Événements publiés : {{ $totalEvents }}</li>
    <li>Revenu total : {{ number_format($totalRevenue) }} FCFA</li>
    <li>Taux de remplissage moyen : {{ $avgFillRate }} %</li>
</ul>

<h2>Top 5 événements les plus populaires</h2>
<table>
    <thead>
    <tr>
        <th>Événement</th>
        <th>Inscrits</th>
    </tr>
    </thead>
    <tbody>
    @foreach($topEvents as $event)
        <tr>
            <td>{{ $event->titre }}</td>
            <td>{{ $event->participants_count }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h2>Répartition des rôles</h2>
<ul>
    <li>Participants : {{ $roles['participant'] }}</li>
    <li>Organisateurs : {{ $roles['organisateur'] }}</li>
    <li>Admins : {{ $roles['admin'] }}</li>
</ul>
</body>
</html>
