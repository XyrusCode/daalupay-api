<?php

namespace DaluPay\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class MigrationController extends BaseController
{

	public function index() {
		return view('migration'); // Keep existing view logic
	}

	public function getMigrations() {
		Artisan::call('migrate:status');

		$output = Artisan::output();

		// format the output
		$output = explode("\n", $output);

		return response()->json($output);
	}

    public function runMigrations()
    {
        try {
            // Start a new migration instance to get a list of pending migrations
            $migrations = Artisan::call('migrate:status');
            Artisan::call('migrate', ['--force' => true]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Parse the exception to get the file causing the issue if possible
            $errorMessage = $e->getMessage();

            // Check for any specific migration file in the error message
            $migrationFile = null;
            if (preg_match('/\[(.*?)\]/', $errorMessage, $matches)) {
                $migrationFile = $matches[1];
            }

            // Return a more descriptive error response
            return response()->json([
                'success' => false,
                'message' => "Migration error in: {$migrationFile}. " . $errorMessage
            ]);
        }
    }


	public function rollbackMigrations() {
		try {
			Artisan::call('migrate:rollback');
			return response()->json(['success' => true]);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'message' => $e->getMessage()]);
		}
	}

	public function runSeeds() {
		try {
			// using --force option to run in production mode
			Artisan::call('db:seed', ['--force' => true]);
			return response()->json(['success' => true]);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'message' => $e->getMessage()]);
		}
	}
}
