<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CreatorController extends Controller
{
    /**
     * Author books.
     *
     * @param string $author
     *
     * @return View
     */
    public function author(string $author): View
    {
        $books = $this->retrieve('authors', $author);

        return view('creator', compact('books'));
    }

    /**
     * Writer books.
     *
     * @param string $author
     *
     * @return View
     */
    public function writer(string $author): View
    {
        $books = $this->retrieve('writers', $author);

        return view('creator', compact('books'));
    }

    /**
     * Writer books.
     *
     * @param string $designer
     *
     * @return View
     */
    public function characterDesigner(string $designer): View
    {
        $books = $this->retrieve('characterDesigners', $designer);

        return view('creator', compact('books'));
    }

    /**
     * Illustrator books.
     *
     * @param string $author
     *
     * @return View
     */
    public function illustrator(string $author): View
    {
        $books = $this->retrieve('illustrators', $author);

        return view('creator', compact('books'));
    }

    /**
     * Translator books.
     *
     * @param string $author
     *
     * @return View
     */
    public function translator(string $author): View
    {
        $books = $this->retrieve('translators', $author);

        return view('creator', compact('books'));
    }

    /**
     * Retrieve creator books.
     *
     * @param string $type
     * @param string $name
     *
     * @return LengthAwarePaginator
     */
    protected function retrieve(string $type, string $name): LengthAwarePaginator
    {
        return Book::query()
            ->whereHas($type, function (Builder $query) use ($name) {
                $query->where('name', '=', $name);
            })
            ->paginate();
    }
}
