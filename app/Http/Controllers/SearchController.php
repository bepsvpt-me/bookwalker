<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        foreach (['publishers', 'types', 'categories', 'tags'] as $filter) {
            if (!empty($queries[$filter])) {
                $this->{$filter}($builder, $queries[$filter]);
            }
        }

        return view('search', [
            'all' => $this->all($keyword),
            'books' => $builder->paginate(),
        ]);
    }

    /**
     * Get keyword first 1,000 search result.
     *
     * @param string $keyword
     *
     * @return Collection
     */
    protected function all(string $keyword): Collection
    {
        $key = sprintf('search-%s', md5($keyword));

        $ttl = 2 * 60; // 2 minutes

        return Cache::remember($key, $ttl, function () use ($keyword) {
            return Book::search($keyword)
                ->take(1000)
                ->select(['id', 'type_id', 'category_id', 'publisher_id'])
                ->get();
        });
    }

    /**
     * Filter book publisher.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function publishers(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('publisher', $value);
    }

    /**
     * Filter book type.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function types(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('type', $value);
    }

    /**
     * Filter book category.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function categories(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('category', $value);
    }

    /**
     * Filter book tag.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function tags(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('tags', $value);
    }
}
