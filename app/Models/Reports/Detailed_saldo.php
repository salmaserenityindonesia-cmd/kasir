<?php

namespace App\Models\Reports;

class Detailed_saldo extends Report
{
    /**
     * @param array $inputs
     * @return void
     */
    public function create(array $inputs): void
    {
        // No temporary table required for simple log reading
    }

    /**
     * @return array
     */
    public function getDataColumns(): array
    {
        return [
            ['id'            => lang('Reports.sale_id')],
            ['sale_time'     => lang('Reports.date'), 'sortable' => false],
            ['customer_name' => 'Nama Pelanggan'],
            ['employee_name' => lang('Reports.sold_by')],
            ['amount'        => 'Nominal Saldo/Deposit', 'sorter' => 'number_sorter'],
            ['notes'         => lang('Reports.comments')]
        ];
    }

    /**
     * @param array $inputs
     * @return array
     */
    public function getData(array $inputs): array
    {
        $builder = $this->db->table('ospos_saldo_history');
        
        $builder->select('ospos_saldo_history.id,
            ospos_saldo_history.transaction_time as sale_time,
            CONCAT(ospos_customers.first_name, " ", ospos_customers.last_name) as customer_name,
            CONCAT(ospos_employees.first_name, " ", ospos_employees.last_name) as employee_name,
            ospos_saldo_history.amount,
            ospos_saldo_history.notes');
                          
        $builder->join('people AS ospos_customers', 'ospos_customers.person_id = ospos_saldo_history.person_id', 'left');
        $builder->join('people AS ospos_employees', 'ospos_employees.person_id = ospos_saldo_history.employee_id', 'left');
        
        if (isset($inputs['start_date']) && isset($inputs['end_date'])) {
             $builder->where('ospos_saldo_history.transaction_time BETWEEN ' . $this->db->escape($inputs['start_date'] . ' 00:00:00') . ' AND ' . $this->db->escape($inputs['end_date'] . ' 23:59:59'));
        }

        $builder->orderBy('ospos_saldo_history.transaction_time', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * @param array $inputs
     * @return array
     */
    public function getSummaryData(array $inputs): array
    {
        $builder = $this->db->table('ospos_saldo_history');
        
        $builder->select('SUM(amount) AS total_amount');
        
        if (isset($inputs['start_date']) && isset($inputs['end_date'])) {
             $builder->where('transaction_time BETWEEN ' . $this->db->escape($inputs['start_date'] . ' 00:00:00') . ' AND ' . $this->db->escape($inputs['end_date'] . ' 23:59:59'));
        }
        
        return $builder->get()->getRowArray();
    }
}
