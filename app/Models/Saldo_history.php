<?php

namespace App\Models;

use CodeIgniter\Model;

class Saldo_history extends Model
{
    protected $table = 'ospos_saldo_history';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'person_id',
        'employee_id',
        'amount',
        'transaction_time',
        'notes'
    ];

    public function log_topup(int $person_id, int $employee_id, float $amount, string $notes = 'Top Up via Kasir'): bool
    {
        $data = [
            'person_id' => $person_id,
            'employee_id' => $employee_id,
            'amount' => $amount,
            'notes' => $notes,
            'transaction_time' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data, false);
    }

    public function get_claimed_bonus_this_month(array $person_ids): array
    {
        if (empty($person_ids)) {
            return [];
        }

        $month_start = date('Y-m-01 00:00:00');
        $month_end = date('Y-m-t 23:59:59');

        $builder = $this->db->table($this->table);
        $builder->select('person_id');
        $builder->whereIn('person_id', $person_ids);
        $builder->where('notes', 'Top Up Saldo Massal');
        $builder->where("transaction_time BETWEEN '$month_start' AND '$month_end'");
        
        $result = $builder->get()->getResultArray();
        return array_column($result, 'person_id');
    }
}
