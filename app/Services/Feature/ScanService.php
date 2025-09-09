<?php

namespace App\Services\Feature;

use App\Http\Requests\ScanImageRequest;
use Illuminate\Support\Facades\Process;

class ScanService
{
    public function handle(ScanImageRequest $request)
    {
        $path = $request->file('image')->store('imei-images', 'public');
        $filename = basename($path);
        return $this->scan($filename);
    }
    private function scan(string $filename)
    {
        try {
            $python = base_path('venv/Scripts/python.exe');
            $file   = base_path('scan_imei.py');

            $process = \Illuminate\Support\Facades\Process::path(base_path())
                ->run([
                    $python,
                    $file,
                    '--image',
                    $filename
                ]);

            return response()->json(json_decode($process->output(), true));
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ]);
        }
    }
}
