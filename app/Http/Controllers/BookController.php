<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kiwilan\Ebook\Ebook as KiwilanEbook;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:epub,pdf|max:20480',
    ]);

    $path = $request->file('file')->store('books', 'public');
    $fullPath = storage_path("app/public/{$path}");
    // dd($fullPath, file_exists($fullPath));


    $ebook = KiwilanEbook::read($fullPath);
    
    $coverPath = $this->saveCover($ebook);

    $book = Book::create([
        'title' => $ebook->getTitle() ?? 'Titre inconnu',
        'author' => $ebook->getAuthors()[0] ?? 'Auteur inconnu',
        'description' => $ebook->getDescription() ?? '',
        'cover_path' => $coverPath,
        'file_path' => $path,
        'file_type' => strtolower($request->file('file')->getClientOriginalExtension()),
        'file_size' => round($request->file('file')->getSize() / 1024 / 1024, 2), // 🔧 ICI : vraie taille sur disque
    ]);

    return redirect()->route('books.show', $book)->with('success', 'Livre ajouté avec succès!');
}


public function read(Book $book)
{
    $filePath = storage_path('app/public/' . $book->file_path);

    $ebook = KiwilanEbook::read($filePath);
    $epub = $ebook->getParser()?->getEpub();

    $chapters = [];

    if ($epub) {
        $chapters = $epub->getChapters(); // chaque chapitre a label, source, content
    }

    return view('books.read', [
        'ebook' => $book,
        'chapters' => $chapters,
        'fileType' => 'epub', // ou dynamique si besoin
    ]);
}

public function chapter(Book $book, $index)
{
    $filePath = storage_path("app/public/" . $book->file_path);

    $ebook = KiwilanEbook::read($filePath);

    // 👉 On récupère le parser EPUB
    $epub = $ebook->getParser()?->getEpub();

    if (!$epub) {
        return response()->json(['error' => 'Fichier EPUB invalide'], 400);
    }

    // ✅ getChapters ici !
    $chapters = $epub->getChapters();

    $chapter = $chapters[$index] ?? null;

    if (!$chapter) {
        return response()->json(['error' => 'Chapitre introuvable'], 404);
    }

    return response()->json([
        'label' => $chapter->label(),   // Pas getTitle() → c’est getLabel()
        'content' => $chapter->content(),
    ]);
}


    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function destroy(Book $book)
    {
        if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
            Storage::disk('public')->delete($book->cover_path);
        }

        if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
            Storage::disk('public')->delete($book->file_path);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès');
    }

    private function saveCover(KiwilanEbook $ebook)
    {
        if (!$ebook->hasCover()) {
            return null;
        }

        $cover = $ebook->getCover();
        $content = $cover->getContent();

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($content);

        $extension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            default => 'jpg',
        };

        $coverPath = 'covers/' . uniqid() . '.' . $extension;
        Storage::disk('public')->put($coverPath, $content);

        return $coverPath;
    }
}
