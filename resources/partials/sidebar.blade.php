<div class="flex flex-col h-full p-4 bg-gray-100 dark:bg-gray-900">
    <!-- Branding -->
    <div class="mb-6">
        <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900 dark:text-white">
            Daalupay
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 space-y-6">
        <!-- App Info -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">App Info</h3>
            <ul class="mt-2 space-y-2">
                <li>
                    <a href="/api" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">
                        GET /api - App Info
                    </a>
                </li>
                <li>
                    <a href="/api/documentation" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">
                        GET /api/documentation - App Docs
                    </a>
                </li>
            </ul>
        </div>

        <!-- Database -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Database</h3>
            <ul class="mt-2 space-y-2">
                <li><a href="/api/db" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">GET /api/db - DB Overview</a></li>
                <li><a href="/api/db/status" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">GET /api/db/status - Migration Status</a></li>
                <li><a href="/api/db/migrate" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/db/migrate - Run Migrations</a></li>
                <li><a href="/api/db/rollback" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/db/rollback - Rollback Migrations</a></li>
                <li><a href="/api/db/seed" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/db/seed - Seed Database</a></li>
            </ul>
        </div>

        <!-- User Authentication -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Authentication</h3>
            <ul class="mt-2 space-y-2">
                <li><a href="/api/register" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/register - Register User</a></li>
                <li><a href="/api/login" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/login - Login User</a></li>
                <li><a href="/api/logout" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/logout - Logout</a></li>
                <li><a href="/api/forgot-password" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/forgot-password - Forgot Password</a></li>
                <li><a href="/api/reset-password" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/reset-password - Reset Password</a></li>
            </ul>
        </div>

        <!-- Transactions -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Transactions</h3>
            <ul class="mt-2 space-y-2">
                <li><a href="/api/user/transactions" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">GET /api/user/transactions - List Transactions</a></li>
                <li><a href="/api/user/transactions/{id}" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">GET /api/user/transactions/{id} - View Transaction</a></li>
                <li><a href="/api/user/transactions" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/user/transactions - Create Transaction</a></li>
            </ul>
        </div>

        <!-- Admin -->
        <div>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Admin</h3>
            <ul class="mt-2 space-y-2">
                <li><a href="/api/users" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">GET /api/users - List Users</a></li>
                <li><a href="/api/users" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/users - Create User</a></li>
                <li><a href="/api/suspend-user" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/suspend-user - Suspend User</a></li>
                <li><a href="/api/unsuspend-user" class="block text-gray-700 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400">POST /api/unsuspend-user - Unsuspend User</a></li>
            </ul>
        </div>
    </nav>
</div>
