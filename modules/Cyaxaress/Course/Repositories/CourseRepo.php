<?php

namespace Cyaxaress\Course\Repositories;


use Cyaxaress\Course\Models\Course;
use Illuminate\Support\Str;

class CourseRepo
{
    public function store($values)
    {
        return Course::create([
            'teacher_id' => $values->teacher_id,
            'category_id' => $values->category_id,
            'banner_id' => $values->banner_id,
            'title' => $values->title,
            'slug' => Str::slug($values->slug),
            'priority' => $values->priority,
            'price' => $values->price,
            'percent' => $values->percent,
            'type' => $values->type,
            'status' => $values->status,
            'body' => $values->body,
            "Confirmation_status" => Course::CONFIRMATION_STATUS_PENDING
        ]);
    }

    public function paginate()
    {
        return Course::paginate();
    }

    public function findbyId($id)
    {
        return Course::findOrFail($id);
    }

    public function update($id, $value)
    {
        return Course::where('id', $id)->update([
            'teacher_id' => $value->teacher_id,
            'category_id' => $value->category_id,
            'banner_id' => $value->banner_id,
            'title' => $value->title,
            'slug' => Str::slug($value->slug),
            'priority' => $value->priority,
            'price' => $value->price,
            'percent' => $value->percent,
            'type' => $value->type,
            'status' => $value->status,
            'body' => $value->body,
            "confirmation_status" => Course::CONFIRMATION_STATUS_PENDING
        ]);

    }

    public function updateConfimaitonStatus($id, string $status)
    {
        return Course::where('id', $id)->update(['confirmation_status' => $status]);
    }

    public function updatStatus($id, string $status)
    {
        return Course::where('id', $id)->update(['status' => $status]);
    }
}
