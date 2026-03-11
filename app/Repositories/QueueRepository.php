<?php

namespace App\Repositories;

use App\Models\Queues;

class QueueRepository
{
    // Global waiting queues (with users + services)
    public function getWaitingQueues()
    {
        return Queues::query()
            ->from('queues as q')                          // adjust to 'Queues as q' if your actual table is capitalized
            ->join('users as u', 'u.section_id', '=', 'q.service_id')  // <-- match section_id to service_id
            ->join('services as s', 's.id', '=', 'q.service_id')
            ->where('q.status', 'waiting')
            ->select([
                'q.id',
                'q.queue_number as que_number',
                'q.first_name',
                'q.middle_initial',
                'q.last_name',
                'q.priority as priorit',
                'q.queue_date',
                's.service_name',
                'u.id as user_id',
                'u.section_id',
            ])
            ->orderBy('q.queue_date', 'desc')
            ->get();
    }

    
    public function getWaitingQueuesForSection(int $sectionId)
    {
            // TEMP: prove the filter is correct without any join
            return Queues::query()
             ->from('queues as q')          // keep your alias; your model table is 'queues', good
             ->where('q.status', 'waiting')
             ->where('q.service_id', $sectionId)
             ->select([
                    'q.id',
                    'q.queue_number as que_number',
                    'q.first_name',
                    'q.middle_initial',
                    'q.last_name',
                    'q.priority as priorit',
                    'q.status',
                    'q.queue_date',
                    'q.service_id',            // include it to visually confirm filtering is applied
             ])
                ->orderBy('q.queue_date', 'desc')
                ->get();
    }

    public function getServingQueuesForSection(int $sectionId)
    {
            // TEMP: prove the filter is correct without any join
            return Queues::query()
             ->from('queues as q')          // keep your alias; your model table is 'queues', good
             ->where('q.status', 'serving')
             ->where('q.service_id', $sectionId)
             ->select([
                    'q.id',
                    'q.queue_number as que_number',
                    'q.first_name',
                    'q.middle_initial',
                    'q.last_name',
                    'q.priority as priorit',
                    'q.status',
                    'q.queue_date',
                    'q.service_id',            // include it to visually confirm filtering is applied
             ])
                ->orderBy('q.queue_date', 'desc')
                ->get();
    }

}