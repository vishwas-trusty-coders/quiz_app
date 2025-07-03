<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function questions()
    {
        return Question::whereJsonContains('tag_ids', (string) $this->id)
                   ->orWhereJsonContains('tag_ids', (int) $this->id);
    }

    public function questionCount()
    {
        return $this->questions()->count();
    }
}
