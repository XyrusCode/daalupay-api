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
    </style>
</head>
<body>
    <h1>API Documentation</h1>
    <p>This document outlines the available API routes, their HTTP methods, required authentication, and usage details for the application.</p>

    <h2>Public Routes</h2>
    <table>
        <thead>
            <tr>
                <th>Endpoint</th>
                <th>HTTP Method</th>
                <th>Description</th>
                <th>Middleware</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>/</td>
                <td>GET</td>
                <td>Fetch application info.</td>
                <td>None</td>
            </tr>
            <tr>
                <td>/documentation</td>
                <td>GET</td>
                <td>Fetch API documentation.</td>
                <td>None</td>
            </tr>
            <tr>
                <td>/token</td>
                <td>POST</td>
                <td>Generate a mobile authentication token.</td>
                <td>None</td>
            </tr>
            <tr>
                <td>/register</td>
                <td>POST</td>
                <td>Register a new user.</td>
                <td>Guest</td>
            </tr>
            <tr>
                <td>/login</td>
                <td>POST</td>
                <td>User login.</td>
                <td>Guest</td>
            </tr>
        </tbody>
    </table>

    <h2>User Authenticated Routes</h2>
    <table>
        <thead>
            <tr>
                <th>Endpoint</th>
                <th>HTTP Method</th>
                <th>Description</th>
                <th>Middleware</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>/user</td>
                <td>GET</td>
                <td>Fetch user details.</td>
                <td>auth:sanctum</td>
            </tr>
            <tr>
                <td>/user/stats</td>
                <td>GET</td>
                <td>Fetch user statistics.</td>
                <td>auth:sanctum</td>
            </tr>
            <tr>
                <td>/user/transactions</td>
                <td>GET</td>
                <td>List user transactions.</td>
                <td>auth:sanctum</td>
            </tr>
            <tr>
                <td>/user/wallets</td>
                <td>GET</td>
                <td>List user wallets.</td>
                <td>auth:sanctum</td>
            </tr>
        </tbody>
    </table>

    <h2>Admin Routes</h2>
    <table>
        <thead>
            <tr>
                <th>Endpoint</th>
                <th>HTTP Method</th>
                <th>Description</th>
                <th>Middleware</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>/users</td>
                <td>GET</td>
                <td>List all users.</td>
                <td>auth:sanctum, admin</td>
            </tr>
            <tr>
                <td>/suspend-user</td>
                <td>POST</td>
                <td>Suspend a user account.</td>
                <td>auth:sanctum, admin</td>
            </tr>
            <tr>
                <td>/unsuspend-user</td>
                <td>POST</td>
                <td>Unsuspend a user account.</td>
                <td>auth:sanctum, admin</td>
            </tr>
        </tbody>
    </table>

    <h2>Super Admin Routes</h2>
    <table>
        <thead>
            <tr>
                <th>Endpoint</th>
                <th>HTTP Method</th>
                <th>Description</th>
                <th>Middleware</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>/admins</td>
                <td>GET</td>
                <td>List all admins.</td>
                <td>auth:sanctum, super_admin, verify.browser</td>
            </tr>
            <tr>
                <td>/disable-currency</td>
                <td>POST</td>
                <td>Disable a currency for exchange.</td>
                <td>auth:sanctum, super_admin, verify.browser</td>
            </tr>
            <tr>
                <td>/enable-currency</td>
                <td>POST</td>
                <td>Enable a currency for exchange.</td>
                <td>auth:sanctum, super_admin, verify.browser</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
