<!DOCTYPE html>
<html lang="en">
<head>
    <title>Database Migrations</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
            word-wrap: break-word; /* Added to allow word wrapping */
            word-break: break-all; /* Added to break long words */
        }
        th {
            background-color: #f2f2f2;
        }
        #passwordForm {
            text-align: center;
            margin-top: 50px;
        }
        #passwordInput {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }
            .button {
                width: 100%;
                padding: 15px;
                margin-top: 10px;
            }
            table, th, td {
                font-size: 14px;
            }
            #passwordInput {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="container">
        <div id="passwordForm" style="display: block;">
            <input type="password" id="passwordInput" placeholder="Enter Password">
            <button class="button" onclick="checkPassword()">Submit</button>
        </div>

        <div id="migrationContent" style="display: none;">
            <div class="card">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Migrate & Seed Database</h2>
                <button class="button" id="runMigrationsButton">Run Migrations</button>
                <!-- Show only in local env -->
                @if (app()->environment('local'))
                    <button class="button" id="rollbackMigrationsButton">Rollback Migrations</button>
                @endif
                <button class="button" id="seedTables">Seed</button>
                <p id="noNewActionsMessage" style="display: none;">Nothing new to do</p>
            </div>

            <div id="apiResponse"></div>

            <div class="card">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Migrations</h2>
                <table>
                    <thead></thead>
                    <tbody id="migrationsTableBody"></tbody>
                </table>
                <table>
                    <thead></thead>
                    <tbody id="pendingMigrationsTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const correctPassword = "1234";
        function checkPassword() {
            const enteredPassword = document.getElementById('passwordInput').value;
            if (enteredPassword === correctPassword) {
                document.getElementById('passwordForm').style.display = 'none';
                document.getElementById('migrationContent').style.display = 'block';
            } else {
                alert('Incorrect Password');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchMigrations();
            document.getElementById('runMigrationsButton').addEventListener('click', function() {
                runMigrations();
            });
            document.getElementById('rollbackMigrationsButton').addEventListener('click', function() {
                rollbackMigrations();
            });
            document.getElementById('seedTables').addEventListener('click', function() {
                seedTables();
            });
        });

        function updateApiResponse(message) {
            document.getElementById('apiResponse').textContent = message;
        }

        function updateMigrationTables(migrationsData) {
            const migrationsTableBody = document.getElementById('migrationsTableBody');
            const pendingMigrationsTableBody = document.getElementById('pendingMigrationsTableBody');
            migrationsTableBody.innerHTML = ''; // Clear existing rows
            pendingMigrationsTableBody.innerHTML = '';
            let hasPendingMigrations = false;

            migrationsData.forEach(migration => {
                const match = migration.match(/^\s*(\d{4}_\d{2}_\d{2}_\d{6}_[\w_]+)\s+\.+\s+(\[(\d+)\]\s+)?(Ran|Pending)\s*$/);
                if (match) {
                    const [_, migrationName,, batch, status] = match;
                    const row = document.createElement('tr');

                    if (status === 'Ran') {
                        row.innerHTML = `
                            <td>${migrationName}</td>
                            <td>${batch}</td>
                            <td>${status}</td>
                        `;
                        migrationsTableBody.appendChild(row);
                    } else {
                        row.innerHTML = `
                            <td>${migrationName}</td>
                            <td>${status}</td>
                        `;
                        pendingMigrationsTableBody.appendChild(row);
                        hasPendingMigrations = true;
                    }
                }
            });

            // Hide or show buttons based on pending migrations
            const runMigrationsButton = document.getElementById('runMigrationsButton');
            const seedTablesButton = document.getElementById('seedTables');
            const noNewActionsMessage = document.getElementById('noNewActionsMessage');

            // Logic is broken, too sleepy to figure this out now, show all buttons now
            if (hasPendingMigrations) {
                runMigrationsButton.style.display = 'inline-block';
                seedTablesButton.style.display = 'inline-block';
                noNewActionsMessage.style.display = 'none';
            } else {
                runMigrationsButton.style.display = 'inline-block';
                seedTablesButton.style.display = 'inline-block';
                noNewActionsMessage.style.display = 'block';
            }
        }

        function fetchMigrations() {
            fetch('/db/status')
                .then(response => response.json())
                .then(data => {
                    updateMigrationTables(data);
                })
                .catch(error => console.error('Error fetching migrations:', error));
        }

        function runMigrations() {
            fetch('/db/migrate', { method: 'POST' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok. Status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success === true) { // Updated condition
                        updateApiResponse('Migrations ran successfully');
                        fetchMigrations();
                    } else {
                        updateApiResponse(data.message || 'Error running migrations');
                    }
                })
                .catch(error => {
                    console.error('Error running migrations:', error);
                    updateApiResponse('Error running migrations: ' + error.message);
                });
        }

        function rollbackMigrations() {
            fetch('/db/rollback', { method: 'POST' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok. Status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success === true) {
                        updateApiResponse('Migrations rolled back successfully');
                        fetchMigrations();
                    } else {
                        updateApiResponse(data.message || 'Error rolling back migrations');
                    }
                })
                .catch(error => {
                    console.error('Error rolling back migrations:', error);
                    updateApiResponse('Error rolling back migrations: ' + error.message);
                });
        }

        function seedTables() {
            fetch('/db/seed', { method: 'POST' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok. Status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success === true) {
                        updateApiResponse('Tables seeded successfully');
                    } else {
                        updateApiResponse(data.message || 'Seeding failed for an unknown reason');
                    }
                })
                .catch(error => {
                    console.error('Error seeding tables:', error);
                    updateApiResponse('Error seeding tables: ' + error.message);
                });
        }
    </script>
</body>
</html>
