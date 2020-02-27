<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

final class CreatorController extends Controller
{
    /**
     * Author books.
     *
     * @param string $name
     *
     * @return View
     */
    public function author(string $name): View
    {
        $books = $this->retrieve('authors', $name);

        return view('creator', compact('books'));
    }

    /**
     * Writer books.
     *
     * @param string $name
     *
     * @return View
     */
    public function writer(string $name): View
    {
        $books = $this->retrieve('writers', $name);

        return view('creator', compact('books'));
    }

    /**
     * Writer books.
     *
     * @param string $name
     *
     * @return View
     */
    public function characterDesigner(string $name): View
    {
        $books = $this->retrieve('characterDesigners', $name);

        return view('creator', compact('books'));
    }

    /**
     * Illustrator books.
     *
     * @param string $name
     *
     * @return View
     */
    public function illustrator(string $name): View
    {
        $books = $this->retrieve('illustrators', $name);

        return view('creator', compact('books'));
    }

    /**
     * Translator books.
     *
     * @param string $name
     *
     * @return View
     */
    public function translator(string $name): View
    {
        $books = $this->retrieve('translators', $name);

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
