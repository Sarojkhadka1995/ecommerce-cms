<?php

namespace App\Http\Controllers\System\language;

use App\Exports\TranslationExport;
use App\Http\Controllers\System\ResourceController;
use App\Http\Requests\system\uploadExcel;
use App\Imports\TranslationImport;
use App\Model\Language;
use App\Model\Locale;
use App\Services\System\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends ResourceController
{
    public function __construct(TranslationService $translationService)
    {
        parent::__construct($translationService);
    }
    public function moduleName()
    {
        return 'translations';
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\system\translationRequest';
    }


    public function viewFolder()
    {
        return 'system.translation';
    }

    public function update($id)
    {
        $request = app()->make($this->storeValidationRequest());
        $this->service->update($request, $id);

        return response()->json(['status' => 'OK'], 200);
    }

    public function downloadSample()
    {
        $file_path = public_path('sampleTranslation/sample.xls');

        return response()->download($file_path);
    }

    public function downloadExcel(Request $request)
    {
        $translate = [];
        $filename = 'translation.xls';
        $langShortCodes = Language::pluck('language_code')->toArray();

        foreach ($langShortCodes as $lang) {
            $jsonFileName = "{$lang}.json";
            $jsonFilePath = resource_path('lang') . '/' . $jsonFileName;

            if (file_exists($jsonFilePath)) {
                $existingContent = file_get_contents($jsonFilePath);
                $existingTranslations = json_decode($existingContent, true);
                $translate[$lang] = $existingTranslations;
            }
        }
        // Initialize the new array
        $newArray = [];

// Iterate over each language
        foreach ($translate as $languageCode => $translations) {
            // Iterate over each translation key
            foreach ($translations as $key => $translation) {
                // Add the translation to the new array
                if (!isset($newArray[$key])) {
                    $newArray[$key] = [];
                }
                $newArray[$key][$languageCode] = $translation;
            }
        }

        return \Excel::download(new TranslationExport($newArray), $filename);
    }

    public function uploadExcel(uploadExcel $request)
    {
        $file = $request->excel_file;
        $fileExtension = $file->getClientOriginalExtension();
        if (!in_array($fileExtension, ['xlsx', 'xls'])) {
            return back()->withErrors(['alert-danger' => 'The file type must be xls or xlsx!']);
        }

        try {
            $contents = \Excel::import(new TranslationImport(), $file);
            $uploadedData = $contents->toArray($contents, $file);

            if (count($uploadedData[0]) <= 1) {
                return back()->withErrors(['alert-danger' => 'The file does not contain any translation content']);
            }
            $heading = $this->removeSpacesHeading($uploadedData[0][0]);
            $langShortCodes = Language::pluck('language_code')->toArray();

            array_unshift($langShortCodes, 'key');

            $checkValid = array_diff($heading, $langShortCodes);

            if (count($checkValid) > 0) {
                return back()->withErrors(['alert-danger' => 'Invalid translation content or the provided language may not be available.']);
            }
            unset($uploadedData[0][0]); // removing header content from file

            $this->parseAndUploadData($uploadedData, $heading);

            return back()->withErrors(['alert-success' => 'The translations successfully uploaded.']);
        } catch (\Exception $e) {
            return back()->withErrors(['alert-danger' => 'There was some problem in uploading translations.']);
        }
    }

    public function removeSpacesHeading($heading)
    {
        $removed = [];
        foreach ($heading as $key => $value) {
            $removed[$key] = strtolower(trim($value));
        }

        return $removed;
    }

    public function parseAndUploadData($data, $heading)
    {
        $langShortCodes = Language::pluck('language_code')->toArray();
        $directory = resource_path('lang');
        if (!is_dir($directory)) {
            \File::makeDirectory($directory, $mode = 0755, true);
        }
        foreach ($langShortCodes as $lang) {
            $jsonFileName = "{$lang}.json";
            $jsonFilePath = "{$directory}/{$jsonFileName}";

            if (file_exists($jsonFilePath) && in_array($lang, $heading)) {
                $existingContent = file_get_contents($jsonFilePath);
                $existingTranslations = json_decode($existingContent, true);

                $filteredTrans = $this->getTranslation($heading, $lang, $data[0]);

                $filteredData = [];
                foreach ($filteredTrans as $key => $translation) {
                    if (isset($existingTranslations[$key])) {
                        $filteredData[$key] = $translation;

                        $mergedTranslations = array_merge($existingTranslations, $filteredData);

                        $jsonContentString = json_encode($mergedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        file_put_contents($jsonFilePath, $jsonContentString);
                    }else{
                        $jsonContentString = json_encode($filteredData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        file_put_contents($jsonFilePath, $jsonContentString);
                    }
                }
            }
        }

        $arrayT = [];

        foreach ($data[0] as $key => $value) {
            $word = strtolower(trim(str_replace('.', '', $value[0])));
            $lang = Locale::where('key', $word)->first();
            $updated = $this->formatText($value, $heading);
            if (isset($lang) || $lang !== null) {
                $lang->update([
                    'text' => $updated,
                ]);
            } else {
                Locale::create([
                    'key' => $word,
                    'text' => $updated,
                ]);
            }
        }

        return $arrayT;
    }

    public
    function formatText($data, $heading)
    {
        unset($data[0]); // removing key field
        $arrayT = [];
        foreach ($data as $key => $value) {
            $arrayT[$heading[$key]] = $value;
        }

        return $arrayT;
    }

// Define a function to filter translations by language code
    function getTranslation($heading, $languageCode, $translationsArray)
    {
        // Find the index of the language in the heading array
        $langIndex = array_search($languageCode, $heading);

        if ($langIndex !== false) {
            // Initialize an array to store filtered data
            $filteredData = [];

            // Iterate through the data arrays
            foreach ($translationsArray as $dataArray) {
                // Check if the current data array has a translation for the specified language
                if (isset($dataArray[$langIndex])) {
                    // Add the translation to the filtered data array
                    $filteredData[$dataArray[0]] = $dataArray[$langIndex];
                }
            }

            return $filteredData;
        }
    }
}
