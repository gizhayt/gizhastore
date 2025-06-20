<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientConnectionController extends Controller
{
    /**
     * Connect a user to a client.
     */
    public function connectUserToClient($userId)
    {
        // Find the user
        $user = User::findOrFail($userId);
        
        // Check if a client with this email already exists
        $client = Client::where('email', $user->email)->first();
        
        if (!$client) {
            // Create a new client record if none exists
            $client = Client::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password, // Use the same hashed password
                'remember_token' => $user->remember_token,
            ]);
            
            return "Created new client and linked to user ID: {$user->id}";
        } else {
            // Update existing client to link with user
            $client->user_id = $user->id;
            $client->save();
            
            return "Linked existing client to user ID: {$user->id}";
        }
    }
    
    /**
     * Alternative method: Create a new client with the same ID as the user.
     */
    public function createClientWithSameId($userId)
    {
        // Find the user
        $user = User::findOrFail($userId);
        
        // Check if a client with this ID already exists
        $existingClient = Client::find($userId);
        
        if ($existingClient) {
            return "Client with ID {$userId} already exists";
        }
        
        // Create a new client with the same ID
        DB::statement("INSERT INTO clients (id, user_id, name, email, password, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, NOW(), NOW())", 
                      [$user->id, $user->id, $user->name, $user->email, $user->password]);
        
        return "Created new client with ID {$user->id} matching user ID";
    }
}