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
        $routesJsonPath = resource_path('doc-routes.json');
        $modelsJsonPath = resource_path('doc-models.json');
        $routes = [];
        $models = [];
        if (file_exists($routesJsonPath)) {
            $routesJsonContent = file_get_contents($routesJsonPath);
            if ($routesJsonContent !== false) {
                $routes = json_decode($routesJsonContent, true) ?? [];
            }
        }

        if (file_exists($modelsJsonPath)) {
            $modelsJsonContent = file_get_contents($modelsJsonPath);
            if ($modelsJsonContent !== false) {
                $models = json_decode($modelsJsonContent, true) ?? [];
            }
        }
    @endphp

    @if(!empty($routes))
        @foreach ($routes['routes'] as $groupData)
            <div class="route-group">
                <h2 class="group-title">{{ $groupData['group'] ?? 'Unnamed Group' }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Endpoint</th>
                            <th>HTTP Method</th>
                            <th>Description</th>
                            <th>Middleware</th>
                            <th>Query Params</th>
                            <th>Request Body</th>
                            <th>Response Body</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupData['routes'] as $route)
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

    @if(!empty($models))
        @foreach ($models as $model)
            <h2>{{ $model['name'] }}</h2>
            <p>{{ $model['description'] }}</p>
            <table>
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($model['fields'] as $field)
                        <tr>
                            <td>{{ $field['name'] }}</td>
                            <td>{{ $field['type'] }}</td>
                            <td>{{ $field['description'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif



<hr>
    @if(empty($routes) && empty($models))
        <div class="route-group">
            <p>No API documentation available.</p>
        </div>
    @endif
</body>
</html>
