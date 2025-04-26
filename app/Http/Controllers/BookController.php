<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kiwilan\Ebook\Ebook as KiwilanEbook;
use ZipArchive;

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
    


    $ebook = KiwilanEbook::read($fullPath);
    [$coverPath,$coverBase64]= $this->saveCover($ebook);

    $book = Book::create([
        'title' => $ebook->getTitle() ?? 'Titre inconnu',
        'author' => $ebook->getAuthors()[0] ?? 'Auteur inconnu',
        'description' => $ebook->getDescription() ?? '',
        'cover_path' => $coverPath,
        'cover_base64' => $coverBase64, // Base64 (si existe)
        'file_path' => $path,
        'file_type' => strtolower($request->file('file')->getClientOriginalExtension()),
        'file_size' => round($request->file('file')->getSize() / 1024 / 1024, 2), // 🔧 ICI : vraie taille sur disque
    ]);
    
     // 🔥 Extraire toutes les images juste après
    $this->extractEbookImages($fullPath, $book->id);

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
        'fileType' => $book->file_type, // ou dynamique si besoin
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
    // On extrait le contenu du chapitre    
    $content = $chapter->content();
    // On remplace les src des images par le bon chemin
    $content = $this->updateChapterImages($content, $book->id);

    // ✅ Injecter un petit style pour limiter la taille des images
    $style = '<style>img { max-width: 100%; height: auto; display: block; margin: 0 auto; }</style>';

    $content = $style . $content;

    return response()->json([
        'label' => $chapter->label(),   // Pas getTitle() → c’est getLabel()
        'content' => $content,
    ]);
}


    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function destroy(Book $book)
    {
        // Supprimer la couverture
        if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
            Storage::disk('public')->delete($book->cover_path);
        }
    
        // Supprimer le fichier EPUB ou PDF
        if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
            Storage::disk('public')->delete($book->file_path);
        }
    
        // Supprimer le dossier d'images du livre, puis tout le dossier du livre
        $imagesDirectory = "books/{$book->id}/images";
        $bookDirectory = "books/{$book->id}";
    
        if (Storage::disk('public')->exists($imagesDirectory)) {
            Storage::disk('public')->deleteDirectory($imagesDirectory);
        }
    
        if (Storage::disk('public')->exists($bookDirectory)) {
            Storage::disk('public')->deleteDirectory($bookDirectory);
        }
    
        // Enfin supprimer l'entrée en BDD
        $book->delete();
    
        return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès');
    }
    


private function saveCover(KiwilanEbook $ebook)
{
    if (!$ebook->hasCover()) {
        return [null, null]; // ni fichier ni base64
    }

    $cover = $ebook->getCover();
    $content = $cover->getContents();

    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($content);

    $extension = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        default => 'jpg',
    };

    $coverPath = 'covers/' . uniqid() . '.' . $extension;

    try {
        // COMPRESSER ici avant de sauvegarder
        $compressedContent = $this->compressImage($content);

        Storage::disk('public')->put($coverPath, $compressedContent);

        return [$coverPath, null]; // réussite → chemin fichier
    } catch (\Exception $e) {
        // Si erreur, retourne Base64
        return [null, $cover->getContents(true)];
    }
}



private function extractEbookImages(string $filePath, int $bookId): void
{
    $zip = new ZipArchive();

    if ($zip->open($filePath) === TRUE) {
        $destinationPath = storage_path("app/public/books/{$bookId}/images");

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $filename = $stat['name'];

            // Cherche les fichiers images uniquement
            if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $filename)) {
                $stream = $zip->getStream($filename);

                if ($stream) {
                    $fileContent = stream_get_contents($stream);
                    $relativeFilename = basename($filename);
                    file_put_contents($destinationPath . '/' . $relativeFilename, $fileContent);
                    fclose($stream);
                }
            }
        }

        $zip->close();
    }
}

private function updateChapterImages(string $html, int $bookId): string
{
    return preg_replace_callback('/<img\s+[^>]*src=["\']([^"\']+)["\']/i', function ($matches) use ($bookId) {
        $src = $matches[1];

        // On garde uniquement le nom de fichier
        $filename = basename($src);

        // Nouveau chemin public
        $newSrc = asset("storage/books/{$bookId}/images/{$filename}");

        // Remplacer dans la balise img
        return str_replace($src, $newSrc, $matches[0]);
    }, $html);
}

private function compressImage(string $content): string
{
    $image = @imagecreatefromstring($content);

    if (!$image) {
        return $content; // Retourne original si erreur de compression
    }

    ob_start();
    imagejpeg($image, null, 75); // 75% qualité
    $compressed = ob_get_clean();

    imagedestroy($image);

    return $compressed;
}


}


