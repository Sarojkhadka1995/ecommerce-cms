<?php

namespace App\Exports;

use App\Model\Language;
use App\Model\Locale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TranslationExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct()
    {
        //
    }

    public function view(): View
    {
        return view('system.exports.translations', [
            'translations' => Locale::get(),
            'languages' => Language::get(),
        ]);
    }
}
