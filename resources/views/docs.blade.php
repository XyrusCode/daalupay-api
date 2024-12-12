<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleAccordion(id) {
            const section = document.getElementById(id);
            section.classList.toggle('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 dark:text-white transition-colors duration-300">
    <div class="max-w-7xl mx-auto p-6">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-2">API Documentation</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">Overview of API routes and models.</p>
        </header>

        <!-- Routes Section -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Routes</h2>
            @foreach ($groupedRoutes as $groupName => $routes)
                <div class="mb-6">
                    <button onclick="toggleAccordion('{{ Str::slug($groupName) }}')" class="w-full text-left bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white p-3 rounded-lg">
                        {{ $groupName }} Routes
                    </button>
                    <div id="{{ Str::slug($groupName) }}" class="hidden mt-4">
                        <table class="w-full table-auto border-collapse border border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                                    <th class="border border-gray-300 px-4 py-2">Method</th>
                                    <th class="border border-gray-300 px-4 py-2">URI</th>
                                    <th class="border border-gray-300 px-4 py-2">Action</th>
                                    <th class="border border-gray-300 px-4 py-2">Middleware</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($routes as $route)
                                    <tr class="even:bg-gray-50 dark:even:bg-gray-700">
                                        <td class="border border-gray-300 px-4 py-2">{{ $route['method'] }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $route['uri'] }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $route['action'] }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            {{ implode(', ', $route['middleware']) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Models Section -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Available Models</h2>
            @forelse ($models as $model)
                <div class="mb-4">
                    <button onclick="toggleAccordion('{{ Str::slug($model['name']) }}')" class="w-full text-left bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white p-3 rounded-lg">
                        {{ $model['name'] }}
                    </button>
                    <div id="{{ Str::slug($model['name']) }}" class="hidden mt-2">
                        @if (!empty($model['fillable']))
                            <ul class="list-disc pl-6">
                                @foreach ($model['fillable'] as $field)
                                    <li>{{ $field }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">No fillable fields defined for this model.</p>
                        @endif
                    </div>
                </div>
            @empty
                <p>No models found.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
