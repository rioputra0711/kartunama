<?php

namespace App\Http\Controllers;

use App\Models\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;

class BusinessCardController extends Controller
{
    public function index(Request $request)
    {
        $query = BusinessCard::query();
        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('company', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }
        $cards = $query->latest()->paginate(12);
        return view('vault.index', compact('cards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'card_image' => 'required|file|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        $file = $request->file('card_image');
        
        $stream = fopen($file->getRealPath(), 'r');
        $header = fread($stream, 12);
        fclose($stream);

        $isJpeg = str_starts_with($header, "\xFF\xD8\xFF");
        $isPng = str_starts_with($header, "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A");
        $isWebp = str_starts_with($header, "RIFF") && substr($header, 8, 4) === "WEBP";

        if (!($isJpeg || $isPng || $isWebp)) {
            return back()->withErrors(['card_image' => 'Invalid file magic bytes. File format not allowed.']);
        }

        $hashName = hash_file('sha256', $file->getRealPath()) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('cards', $hashName, 'public');
        $absolutePath = storage_path('app/public/' . $path);

        $extractedText = "";
        try {
            $tesseract = new TesseractOCR($absolutePath);
            $tesseract->executable('C:\Program Files\Tesseract-OCR\tesseract.exe');
            $extractedText = $tesseract->run();
        } catch (\Exception $e) {
            $extractedText = "OCR Error: " . $e->getMessage();
        }

        $name = 'Unknown Name';
        $email = null;
        $phone = null;
        $company = 'Unknown Company';

        if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $extractedText, $matches)) {
            $email = $matches[0];
        }
        if (preg_match('/(\+62|62|0)[0-9\-\s]{9,15}/', $extractedText, $matches)) {
            $phone = trim($matches[0]);
        }

        $lines = array_filter(explode("\n", $extractedText));
        if (count($lines) > 0) {
            $name = trim(reset($lines));
            if (strlen($name) > 50) $name = 'Extracted Contact';
        }

        $card = BusinessCard::create([
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'name' => $name,
            'company' => $company,
            'title' => 'Professional',
            'phone' => $phone ?? '+62 812-0000-0000',
            'email' => $email ?? 'noemail@domain.com',
            'confidence_score' => 0.88,
            'raw_ocr_json' => json_encode(['raw_text' => $extractedText]),
        ]);

        return redirect()->route('cards.index')->with('success', 'Kartu nama berhasil dipindai dengan Tesseract OCR!');
    }

    public function destroy(BusinessCard $card)
    {
        if ($card->file_path && Storage::disk('public')->exists($card->file_path)) {
            Storage::disk('public')->delete($card->file_path);
        }
        $card->delete();
        return redirect()->route('cards.index')->with('success', 'Kartu nama berhasil dihapus.');
    }
}
