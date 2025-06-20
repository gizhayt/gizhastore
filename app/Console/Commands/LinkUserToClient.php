<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class LinkUserToClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:link-user-to-client {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link a user to a client record or create one if needed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        // Find the user
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }
        
        $this->info("Attempting to link user {$user->name} (ID: {$user->id})...");
        
        // Method 1: Check if a client with the same email exists
        $client = Client::where('email', $user->email)->first();
        
        if ($client) {
            $client->user_id = $user->id;
            $client->save();
            $this->info("Updated existing client record (ID: {$client->id}) to link with user");
            return 0;
        }
        
        // Method 2: Create a new client with forced ID to match user ID
        $this->info("Creating new client with same ID as user...");
        
        try {
            // Use DB::statement to force the ID
            DB::statement("INSERT INTO clients (id, user_id, name, email, password, created_at, updated_at) 
                          VALUES (?, ?, ?, ?, ?, NOW(), NOW())", 
                          [$user->id, $user->id, $user->name, $user->email, $user->password]);
            
            $this->info("Successfully created client with ID {$user->id}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Error creating client: " . $e->getMessage());
            
            // Fallback: Create client with auto-increment ID
            $this->info("Trying fallback method...");
            
            try {
                $client = Client::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password,
                ]);
                
                $this->info("Created client with ID {$client->id} linked to user {$user->id}");
                return 0;
            } catch (\Exception $e2) {
                $this->error("Fallback failed: " . $e2->getMessage());
                return 1;
            }
        }
    }
}