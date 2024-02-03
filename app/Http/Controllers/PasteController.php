<?php

namespace App\Http\Controllers;

use App\Exceptions\SpamAttemptException;
use App\Models\Paste;
use App\Services\Highlighter;
use App\Services\LanguageDetectorService;
use App\Services\Slugger;
use App\Services\SpamDetectorService;
use App\Support\StringUtils;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PasteController extends Controller
{
    public function create()
    {
        return view('create');
    }

    public function store(Request $request, Highlighter $highlighter, SpamDetectorService $spamDetector)
    {
        if ($request->file('paste')) {
            $file = $request->file('paste');

            if ($file->getSize() > 64 * 1024) {
                throw new PostTooLargeException();
            }

            $title = $file->getFilename();
            $content = $file->get();
            $language = $highlighter->getLanguageNameByMime($file->getMimeType());
        } else {
            $title = $request->get('title');
            $content = $request->get('content') ?? '';
            $language = $request->get('language');
        }

        if (trim($content) === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', 'Cannot create a paste with empty body')
                ->with('alert-type', 'error');
        }

        if ($spamDetector->isSpamPaste($content)) {
            throw new SpamAttemptException();
        }

        $paste = new Paste();
        $paste->author = $request->get('author');
        $paste->author_ip = $request->getClientIp();
        $paste->title = Str::limit($title, 31, '…');
        $paste->description = $request->get('description');
        $paste->language = $language;
        $paste->key = $request->get('key') !== null ? bcrypt($request->get('key')) : null;
        $paste->content = $content;
        $paste->save();

        return redirect()
            ->route('show', $paste->slug)
            ->with('alert', 'Paste has been created');
    }

    public function show(Paste $paste, Highlighter $highlighter)
    {
        $language = $paste->language;
        if ($highlighter->normalizeLanguageName($language) === 'plaintext') {
            $language = 'plaintext';
        }

        $highlightTimeStart = microtime(true) * 1000;
        $content = $highlighter->highlight($paste->content, $language);
        $highlightTimeFinish = microtime(true) * 1000;

        return view('show', [
            'content' => $content,
            'language' => $language,
            'paste' => $paste,
            'renderTime' => round($highlightTimeFinish - $highlightTimeStart),
            'title' => $paste->title,
        ]);
    }

    public function showRaw(Paste $paste)
    {
        return response($paste->content)
            ->header('Content-Type', 'text/plain');
    }

    public function download(Paste $paste, Highlighter $highlighter)
    {
        $extension = $highlighter->getExtensionByLanguageName($paste->language ?? 'plaintext');

        $filename = $paste->slug;
        if ($paste->title !== null) {
            $filename = StringUtils::toFilename($paste->title, $paste->slug);
        }

        return response()
            ->streamDownload(function () use ($paste) {
                echo $paste->content;
            }, "{$filename}{$extension}");
    }

    public function edit(Paste $paste)
    {
        if (! $paste->key) {
            return redirect()
                ->back()
                ->with('alert', 'Pastes which were given no key during their creation cannot be edited')
                ->with('alert-type', 'error');
        }

        return view('edit', [
            'paste' => $paste,
            'title' => page_title('edit'),
        ]);
    }

    public function update(Request $request, Paste $paste, SpamDetectorService $spamDetector)
    {
        if (! $paste->key) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', 'Pastes which were given no key during their creation cannot be edited')
                ->with('alert-type', 'error');
        }

        if (password_verify($request->get('key'), $paste->key) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', 'The given key is invalid')
                ->with('alert-type', 'error');
        }

        $content = $request->get('content') ?? '';
        if (trim($content) === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with('alert', 'Cannot create a paste with empty body')
                ->with('alert-type', 'error');
        }

        if ($spamDetector->isSpamPaste($content)) {
            throw new SpamAttemptException();
        }

        $paste->author = $request->get('author');
        $paste->title = Str::limit($request->get('title'), 31, '…');
        $paste->description = $request->get('description');
        $paste->language = $request->get('language');
        $paste->content = $content;
        $paste->save();

        return redirect()
            ->route('show', $paste->slug)
            ->with('alert', 'Paste has been updated');
    }

    public function removeShow(Paste $paste)
    {
        if (! $paste->key) {
            return redirect()
                ->back()
                ->with('alert', 'Pastes which were given no key during their creation cannot be removed')
                ->with('alert-type', 'error');
        }

        return view('remove', [
            'paste' => $paste,
            'title' => $paste->title,
        ]);
    }

    public function remove(Request $request, Paste $paste, Slugger $slugger)
    {
        if (! $paste->key) {
            return redirect()
                ->back()
                ->with('alert', 'Pastes which were given no key during their creation cannot be removed')
                ->with('alert-type', 'error');
        }

        if (! password_verify($request->get('key'), $paste->key)) {
            return redirect()
                ->back()
                ->with('alert', 'The given key is invalid')
                ->with('alert-type', 'error');
        }

        $pasteHashid = $slugger->encode($paste->id);
        $paste->delete();

        return redirect()
            ->route('index')
            ->with('alert', "Paste {$pasteHashid} has been removed");
    }

    public function detectLanguage(
        Request $request,
        LanguageDetectorService $languageDetector,
        Highlighter $highlighter
    ): JsonResponse {
        $content = $request->get('content') ?? '';
        if (trim($content) === '') {
            return new JsonResponse(['language' => null]);
        }

        $title = $request->get('title');
        if (!empty($title) && $highlighter->getLanguageNameByFilename($title) !== 'plaintext') {
            $extension = array_reverse(explode('.', $title))[0];

            return new JsonResponse(['language' => $extension]);
        }

        $language = $languageDetector->detectLanguage($content);

        return new JsonResponse(['language' => $language]);
    }
}
