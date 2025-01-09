<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        h1, h2 {
            color: #212529;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #dee2e6;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #343a40;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:nth-child(odd) {
            background-color: #fff;
        }
        pre {
            background: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
            overflow: auto;
            max-height: 300px;
        }

        .route-group {
            margin-bottom: 40px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
        }

        .group-title {
            color: #2c3e50;
            border-bottom: 2px solid #3490dc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>API Documentation</h1>
    <p>This document outlines the available API routes, their HTTP methods, required authentication, and usage details for the application.</p>

    @php
        $jsonPath = resource_path('docs.json');
        $docs = [];

        if (file_exists($jsonPath)) {
            $jsonContent = file_get_contents($jsonPath);
            if ($jsonContent !== false) {
                $docs = json_decode($jsonContent, true) ?? [];
            }
        }
    @endphp

    @if(!empty($docs['routes']))
        @foreach ($docs['routes'] as $group)
            <div class="route-group">
                <h2 class="group-title">{{ $group['group'] }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Endpoint</th>
                            <th>HTTP Method</th>
                            <th>Description</th>
                            <th>Middleware</th>
                            <th>Request Body</th>
                            <th>Query Params</th>
                            <th>Response Body</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($group['routes'] as $route)
                            <tr>
                                <td>{{ $route['route'] }}</td>
                                <td>{{ $route['method'] }}</td>
                                <td>{{ $route['description'] }}</td>
                                <td>
                                    @if(isset($route['middleware']) && is_array($route['middleware']) && count($route['middleware']) > 0)
                                        {{ implode(', ', $route['middleware']) }}
                                    @else
                                        None
                                    @endif
                                </td>
                                <td>
                                    @if(isset($route['query_params']))
                                        <pre>{{ json_encode($route['query_params'], JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        None
                                    @endif
                                </td>
                                 <td>
                                    @if(isset($route['request_body']))
                                        <pre>{{ json_encode($route['request_body'], JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        None
                                    @endif
                                </td>
                                <td>
                                    @if(isset($route['response_body']))
                                        <pre>{{ json_encode($route['response_body'], JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        None
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="route-group">
            <p>No API documentation available.</p>
        </div>
    @endif
</body>
</html>
