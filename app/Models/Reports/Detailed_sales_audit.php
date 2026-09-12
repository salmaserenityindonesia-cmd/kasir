<?php

namespace App\Models\Reports;

use CodeIgniter\Database\ResultInterface;

/**
 * Detailed_sales_audit class
 */
class Detailed_sales_audit extends Report
{
    /**
     * @return void
     */
    public function create(array $inputs): void
    {
        $this->create_temp_table($inputs);
    }

    /**
     * @return void
     */
    protected function create_temp_table(array $inputs): void
    {
        // No temp table needed for audit log
    }

    /**
     * @return array
     */
    public function getDataColumns(): array
    {
        return [
            'summary' => [
                ['id' => lang('Reports.audit_id')],
                ['sale_id' => lang('Reports.sale_id')],
                ['action_type' => lang('Reports.action_type')],
                ['changed_by' => lang('Reports.changed_by')],
                ['action_time' => lang('Reports.action_time')],
            ],
            'details' => [
                lang('Reports.field_name'),
                lang('Reports.old_value'),
                lang('Reports.new_value')
            ]
        ];
    }

    /**
     * @return array
     */
    public function getData(array $inputs): array
    {
        $builder = $this->db->table('sales_audit_logs');
        $builder->select('sales_audit_logs.*, people.first_name, people.last_name');
        $builder->join('employees', 'employees.person_id = sales_audit_logs.changed_by_employee_id', 'left');
        $builder->join('people', 'people.person_id = employees.person_id', 'left');
        
        if (isset($inputs['start_date']) && isset($inputs['end_date'])) {
            $builder->where('DATE(action_time) BETWEEN ' . $this->db->escape($inputs['start_date']) . ' AND ' . $this->db->escape($inputs['end_date']));
        }
        
        $builder->orderBy('action_time', 'DESC');
        
        $results = $builder->get()->getResultArray();
        
        $data = [];
        foreach ($results as $row) {
            $employee_name = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
            $details = json_decode($row['details'], true);
            $parsed_details = [];
            
            if (is_array($details)) {
                if ($row['action_type'] == 'DELETE') {
                    $parsed_details[] = [
                        'field' => 'Action Description',
                        'old_value' => 'N/A',
                        'new_value' => 'Deleted Sale #' . $row['sale_id'] . ' (Invoice: ' . ($details['invoice_number'] ?? 'N/A') . ')'
                    ];
                } else {
                    foreach ($details as $key => $values) {
                        if ($key == 'payment_new') {
                             $parsed_details[] = [
                                'field' => 'New Payment Added',
                                'old_value' => 'None',
                                'new_value' => $values['type'] . ' (' . $values['amount'] . ')'
                             ];
                        } elseif (strpos($key, 'payment_') === 0 && isset($values['old_type'])) {
                            $parsed_details[] = [
                                'field' => 'Payment Modified',
                                'old_value' => $values['old_type'] . " (" . $values['old_amount'] . ")",
                                'new_value' => $values['new_type'] . " (" . $values['new_amount'] . ")"
                            ];
                        } else {
                            $parsed_details[] = [
                                'field' => ucwords(str_replace('_', ' ', $key)),
                                'old_value' => is_array($values) ? ($values['old'] ?? '') : '',
                                'new_value' => is_array($values) ? ($values['new'] ?? '') : ''
                            ];
                        }
                    }
                }
            }
            
            $data[] = [
                'id' => $row['id'],
                'sale_id' => $row['sale_id'],
                'action_type' => $row['action_type'],
                'changed_by' => $employee_name,
                'action_time' => $row['action_time'],
                'details' => $parsed_details
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getSummaryData(array $inputs): array
    {
        return [];
    }
}
