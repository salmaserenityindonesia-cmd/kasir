<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Sales Audit Log class
 */
class Sales_audit_log extends Model
{
    protected $table = 'sales_audit_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'sale_id',
        'action_type',
        'changed_by_employee_id',
        'action_time',
        'details'
    ];
    
    public function log_action(int $sale_id, string $action_type, int $employee_id, array $details): bool
    {
        $data = [
            'sale_id' => $sale_id,
            'action_type' => $action_type,
            'changed_by_employee_id' => $employee_id,
            'action_time' => date('Y-m-d H:i:s'),
            'details' => json_encode($details)
        ];
        
        return $this->insert($data);
    }
}
