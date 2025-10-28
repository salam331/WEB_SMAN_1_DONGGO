<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Request;

class LogService
{
    /**
     * Log an action
     */
    public static function log($action, $model, $modelId = null, $description = null, $oldValues = null, $newValues = null, $meta = null)
    {
        $user = auth()->user();

        if (!$user) {
            return; // Skip logging if no authenticated user
        }

        // Get user role
        $role = $user->roles->first()->name ?? null;

        Log::create([
            'user_id' => $user->id,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'role' => $role,
            'ip_address' => Request::ip(),
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'meta' => $meta,
        ]);
    }

    /**
     * Log create action
     */
    public static function logCreate($model, $modelId, $newValues = null, $description = null)
    {
        self::log('create', $model, $modelId, $description, null, $newValues);
    }

    /**
     * Log update action
     */
    public static function logUpdate($model, $modelId, $oldValues = null, $newValues = null, $description = null)
    {
        self::log('update', $model, $modelId, $description, $oldValues, $newValues);
    }

    /**
     * Log delete action
     */
    public static function logDelete($model, $modelId, $oldValues = null, $description = null)
    {
        self::log('delete', $model, $modelId, $description, $oldValues, null);
    }

    /**
     * Log login action
     */
    public static function logLogin($description = null)
    {
        self::log('login', 'User', auth()->id(), $description);
    }

    /**
     * Log logout action
     */
    public static function logLogout($description = null)
    {
        self::log('logout', 'User', auth()->id(), $description);
    }
}
