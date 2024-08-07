<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Book\BookIndexRequest;
use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class BookIndexController extends Controller
{
    /**
     * Take.
     */
    public const TAKE = \PHP_INT_MAX;

    /**
     * Sort.
     */
    public const SORT = ['id', '-id', 'created_at', '-created_at', 'updated_at', '-updated_at', 'name', '-name'];

    /**
     * Handle the incoming request.
     */
    public function __invoke(BookIndexRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $builder = Book::query();

        $this->select($builder);

        $this->attachRelationships($builder);

        $this->filterSearch($builder, $validated);
        $this->filterId($builder, $validated);

        $this->sort($builder, $validated);

        $data = $builder->paginate($validated['take'] ?? self::TAKE);

        return response()->json($data);
    }

    /**
     * Modify select query.
     */
    protected function select(Builder $builder): void
    {
        $builder->getQuery()->select($builder->qualifyColumn('*'));
    }

    /**
     * Attach relationships.
     */
    protected function attachRelationships(Builder $builder): void
    {
        $builder->with(['user', 'reviews']);
    }

    /**
     * Filter by search.
     */
    protected function filterSearch(Builder $builder, array $validated): void
    {
        if (!Arr::has($validated, 'filter.search')) {
            return;
        }

        Book::scopeSearch($builder, Arr::get($validated, 'filter.search'));
    }

    /**
     * Filter by id.
     */
    protected function filterId(Builder $builder, array $validated): void
    {
        if (!Arr::has($validated, 'filter.id')) {
            return;
        }

        Book::scopeKey($builder, Arr::get($validated, 'filter.id'));
    }

    /**
     * Sort query.
     */
    protected function sort(Builder $builder, array $validated): void
    {
        $sorts = $validated['sort'] ?? [];

        if (! \in_array('id', $sorts, true) && ! \in_array('-id', $sorts, true)) {
            $sorts[] = '-id';
        }

        foreach ($sorts as $sort) {
            if (\str_starts_with($sort, '-')) {
                $builder->getQuery()->orderByDesc($builder->qualifyColumn(\mb_substr($sort, 1)));

                continue;
            }

            $builder->getQuery()->orderBy($builder->qualifyColumn($sort));
        }
    }
}
