<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use ScoutElastic\Builders\SearchBuilder;

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

        $builder = Book::search($keyword);

        $queries = $request->query();

        foreach (['type', 'category', 'tag'] as $filter) {
            if (!empty($queries[$filter])) {
                $this->{$filter}($builder, $queries[$filter]);
            }
        }

        return view('search', ['books' => $builder->paginate()]);
    }

    /**
     * Filter book type.
     *
     * @param SearchBuilder $builder
     * @param string $value
     *
     * @return void
     */
    protected function type(SearchBuilder $builder, string $value): void
    {
        $builder->where('type', Type::query()->find($value)->name);
    }

    /**
     * Filter book category.
     *
     * @param SearchBuilder $builder
     * @param string $value
     *
     * @return void
     */
    protected function category(SearchBuilder $builder, string $value): void
    {
        $builder->where('category', Category::query()->find($value)->name);
    }

    /**
     * Filter book tag.
     *
     * @param SearchBuilder $builder
     * @param string $value
     *
     * @return void
     */
    protected function tag(SearchBuilder $builder, string $value): void
    {
        $builder->whereIn('tags', [Tag::query()->find($value)->name]);
    }
}
