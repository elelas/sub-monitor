<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Subscription extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Subscription::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
    ];

    public static $with = [
        'user',
        'service'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id')->sortable(),
            Text::make('Название', 'title')->nullable()->sortable(),
            Date::make('Дата первого платежа', 'first_payment_date')
                ->sortable()
                ->required(),
            Number::make('Интервал', 'interval_value')
                ->required(),
            Text::make('Тип интервала', 'interval_type')
                ->suggestions([
                    \App\Models\Subscription::DAY_INTERVAL,
                    \App\Models\Subscription::WEEK_INTERVAL,
                    \App\Models\Subscription::MONTH_INTERVAL,
                    \App\Models\Subscription::YEAR_INTERVAL,
                ]),
            Number::make('Сумма платежа', 'payment_amount')
                ->required(),
            Text::make('Валюта', 'currency_code')
                ->suggestions([
                    \App\Models\Subscription::CURRENCY_EUR,
                    \App\Models\Subscription::CURRENCY_USD,
                    \App\Models\Subscription::CURRENCY_RUB,
                ])
                ->default(\App\Models\Subscription::CURRENCY_USD),
            Image::make('Изображение', 'image')->nullable(),
            BelongsTo::make('Сервис', 'service', Service::class)->nullable(),
            Boolean::make('Пролонгация', 'with_prolongation')->default(true),
            BelongsTo::make('Пользователь', 'user', User::class)
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
