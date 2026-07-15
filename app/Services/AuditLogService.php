<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    /**
     * Log a Filament action (view, edit, delete, export, import, etc.)
     */
    public static function logAction(
        string $action,
        ?Model $model = null,
        array $properties = [],
    ): void {
        $log = activity()
            ->useLog('User Actions')
            ->withProperties(array_merge([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->url(),
                'method' => request()->method(),
            ], $properties));

        if ($model) {
            $log->performedOn($model);
        }

        $log->log($action);
    }

    /**
     * Log bulk actions (bulk delete, bulk export, etc.)
     */
    public static function logBulkAction(
        string $action,
        int $recordCount,
        array $recordIds = [],
        array $properties = [],
    ): void {
        activity()
            ->useLog('User Actions')
            ->withProperties(array_merge([
                'record_count' => $recordCount,
                'record_ids' => $recordIds,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ], $properties))
            ->log($action);
    }

    /**
     * Log export action
     */
    public static function logExport(
        string $modelName,
        int $recordCount,
        array $columns = [],
        array $filters = [],
    ): void {
        self::logBulkAction('Export', $recordCount, [], [
            'model' => $modelName,
            'columns' => $columns,
            'filters' => $filters,
            'timestamp' => now(),
        ]);
    }

    /**
     * Log import action
     */
    public static function logImport(
        string $modelName,
        int $successCount,
        int $failureCount,
        array $properties = [],
    ): void {
        activity()
            ->useLog('User Actions')
            ->withProperties(array_merge([
                'model' => $modelName,
                'success_count' => $successCount,
                'failure_count' => $failureCount,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ], $properties))
            ->log('Import');
    }
}
