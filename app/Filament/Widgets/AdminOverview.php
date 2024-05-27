<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\History;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;


class AdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPrice = DB::table('histories')->sum('price');

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalPriceThisMonth = DB::table('histories')
            ->whereMonth('created_at', $currentMonth)
            ->sum('price');

        $totalPriceThisYear = DB::table('histories')
            ->whereYear('created_at', $currentYear)
            ->sum('price');

        return [
            Stat::make('All Users', User::query()->count())
                ->description('All users from our app')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Subscriptions', Subscription::query()->where('status', 'pending')->count())
                ->description('Pending subscriptions')
                ->chart([7, 18, 3, 20, 1, 4, 21])
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Articles', Article::query()->count())
                ->description('All articles')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-book-open')
                ->color('warning'),
            Stat::make('Total Net', $totalPrice)
                ->description('Total net for all time')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('This Month', $totalPriceThisMonth)
                ->description('Total net for this month')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info'),
            Stat::make('This Year', $totalPriceThisYear)
                ->description('Total net for this year')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),
        ];
    }
}
