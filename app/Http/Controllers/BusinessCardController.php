<?php

namespace App\Http\Controllers;

use App\Models\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Gemini;

class BusinessCardController extends Controller
{
    public function index(Request $request)
    {
        $query = BusinessCard::query();
        
        if ($filter = $request->input('filter')) {
            if ($filter === 'work') {
                $query->whereNotNull('company')->where('company', '!=', '');
            } elseif ($filter === 'personal') {
                $query->where(function($q) {
                    $q->whereNull('company')->orWhere('company', '=', '');
                });
            }
        }

        if ($sort = $request->input('sort')) {
            if ($sort === 'recent') {
                $query->latest();
            }
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('company', 'like', "%$search%")
                  ->orWhere('title', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('office_phone', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('website', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('linkedin', 'like', "%$search%");
            });
        }
        $cards = $query->latest()->paginate(6)->withQueryString();
        return view('vault.index', compact('cards'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1',
        ]);

        $query = BusinessCard::query();
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->q}%")
              ->orWhere('company', 'like', "%{$request->q}%")
              ->orWhere('title', 'like', "%{$request->q}%")
              ->orWhere('phone', 'like', "%{$request->q}%")
              ->orWhere('office_phone', 'like', "%{$request->q}%")
              ->orWhere('email', 'like', "%{$request->q}%")
              ->orWhere('website', 'like', "%{$request->q}%")
              ->orWhere('address', 'like', "%{$request->q}%")
              ->orWhere('linkedin', 'like', "%{$request->q}%");
        });

        $cards = $query->latest()->paginate(6)->withQueryString();
        return response()->json([
            'success' => true,
            'html' => view('vault._search_results', compact('cards'))->render(),
        ]);
    }

    public function allContacts(Request $request)
    {
        $cards = BusinessCard::latest()->paginate(12)->withQueryString();
        return view('vault.index', compact('cards'));
    }

    public function recentlyAdded(Request $request)
    {
        $cards = BusinessCard::latest()->take(10)->get();
        return view('vault.index', compact('cards'));
    }

    public function preview(Request $request)
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
        $data = [];
        try {
            $apiKey = env('GEMINI_API_KEY');
            $client = Gemini::client($apiKey);
            
            $imageData = base64_encode(file_get_contents($absolutePath));
            
            $rawMime = $file->getClientMimeType();
            $mimeEnum = match($rawMime) {
                'image/png' => \Gemini\Enums\MimeType::IMAGE_PNG,
                'image/jpeg', 'image/jpg' => \Gemini\Enums\MimeType::IMAGE_JPEG,
                'image/webp' => \Gemini\Enums\MimeType::IMAGE_WEBP,
                'image/heic' => \Gemini\Enums\MimeType::IMAGE_HEIC,
                'image/heif' => \Gemini\Enums\MimeType::IMAGE_HEIF,
                default => \Gemini\Enums\MimeType::IMAGE_JPEG,
            };

            $prompt = 'Analyze this business card image and extract information into a strict JSON format. ONLY output the JSON object, without any markdown formatting or code blocks. Use exactly these keys: "name", "company", "title", "phone", "office_phone", "email", "website", "address", "linkedin". If a field is not found, set its value to an empty string.';

            $response = $client->generativeModel('gemini-3.1-flash-lite')->generateContent([
                $prompt,
                new \Gemini\Data\Blob(
                    mimeType: $mimeEnum,
                    data: $imageData
                )
            ]);
            
            $extractedText = $response->text();
        } catch (\Exception $e) {
            $extractedText = '{"error": "Gemini Error: ' . $e->getMessage() . '"}';
        }

        $cleanedJson = trim(preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $extractedText));
        $data = json_decode($cleanedJson, true) ?? [];

        $query = BusinessCard::query();
        $cards = $query->latest()->paginate(6)->withQueryString();

        return view('vault.index', [
            'cards' => $cards,
            'previewData' => [
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'filesize' => round(filesize($absolutePath) / 1024 / 1024, 2) . ' MB',
                'name' => $data['name'] ?? '',
                'company' => $data['company'] ?? '',
                'title' => $data['title'] ?? '',
                'phone' => $data['phone'] ?? '',
                'office_phone' => $data['office_phone'] ?? '',
                'email' => $data['email'] ?? '',
                'website' => $data['website'] ?? '',
                'address' => $data['address'] ?? '',
                'linkedin' => $data['linkedin'] ?? '',
                'confidence_score' => 0.98,
                'raw_ocr_json' => json_encode(['raw_text' => $extractedText, 'parsed' => $data]),
            ]
        ]);
    }

    public function store(Request $request)
    {
        // Validasi diubah agar menerima entri manual (tanpa gambar)
        $request->validate([
            'file_path' => 'nullable|string',
            'original_filename' => 'nullable|string',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'office_phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'linkedin' => 'nullable|string|max:255',
            'raw_ocr_json' => 'nullable|string',
        ]);

        BusinessCard::create([
            'original_filename' => $request->input('original_filename'),
            'file_path' => $request->input('file_path'),
            'name' => $request->input('name'),
            'company' => $request->input('company'),
            'title' => $request->input('title'),
            'phone' => $request->input('phone'),
            'office_phone' => $request->input('office_phone'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
            'address' => $request->input('address'),
            'linkedin' => $request->input('linkedin'),
            'confidence_score' => $request->input('file_path') ? 0.98 : 1.0, // 100% jika manual
            'raw_ocr_json' => $request->input('raw_ocr_json', '{}'),
        ]);

        return redirect()->route('cards.index')->with('success', 'Kontak berhasil disimpan ke Vault!');
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