<?php

namespace App\Rules\system;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueCaseSenstiveValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $table;
    protected $column;
    protected $ignoreId;

    public function __construct($table, $column, $ignoreId = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->ignoreId = $ignoreId;

    }

    public function passes($attribute, $value)
    {
        $query = DB::table($this->table)
            ->where("{$this->column}", strtolower($value));

        if ($this->ignoreId !== null) {
            $query->where('id', '<>', $this->ignoreId);
        }

        $count = $query->count();

        return $count === 0;
    }

    public function message()
    {
        return "The :attribute has already been taken.";
    }
}
