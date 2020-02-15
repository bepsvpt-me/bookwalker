<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchController extends Controller
{
    /**
     * Search result.
     *
     * @param Request $request
     *
     * @return RedirectResponse|View
     */
    public function index(Request $request)
    {
        $keyword = trim($request->query('keyword', ''));

        if (empty($keyword)) {
            return redirect()->route('home');
        }

        $books = Book::search($keyword)->paginate();

        return view('search', compact('books'));
    }
}
