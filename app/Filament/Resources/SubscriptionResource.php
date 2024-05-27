<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use App\Filament\Resources\SubscriptionResource\RelationManagers\SubscriptionMonthsRelationManager;
use App\Models\Diocese;
use App\Models\Month;
use App\Models\PaymentAccount;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Year;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Subscription';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('year_id')
                    ->label('Year')
                    ->preload()
                    ->options(
                         Year::all()->pluck('name','id')
                    )
                    ->live()
                    ->required(),

                self::getShipmentField($form->getOperation()),

                Select::make('payment_account_id')
                    ->label('Payment account')
                    ->options(PaymentAccount::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approve' => 'Approve',
                        'cancel' => 'Cancel',
                    ])
                    ->native(false)
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric(),
                SpatieMediaLibraryFileUpload::make('Slip')
                    ->collection('payment-slip')
                    ->conversion('thumb')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    protected static function getShipmentField(string $operation)
    {
        if ($operation === 'create') {
            return Select::make('month_id')
                    ->label('Month')
                    ->preload()
                    ->multiple()
                    ->options(function (Get $get) {
                        $yearId = $get('year_id');
                        if ($yearId) {
                            return Month::where('year_id', $yearId)->pluck('name', 'id');
                        }
                        return [];
                    })
                    ->required();
        } else {
            return Select::make('month_id')
                    ->label('Month')
                    ->preload()
                    ->multiple()
                    ->options(function (Get $get, $record) {
                        $yearId = $get('year_id');

                        if ($yearId) {
                            return Month::where('year_id', $yearId)->pluck('name', 'id');
                        } else {
                            return [];
                        }
                    });
        }
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // SpatieMediaLibraryImageColumn::make('Slip')
                //     ->collection('payment-slip')
                //     ->defaultImageUrl(asset('assets/images/default.png'))
                //     ->conversion('thumb'),
                TextColumn::make('user.name'),
                TextColumn::make('user.phone')
                    ->label('Phone'),
                TextColumn::make('user.diocese.name')
                    ->sortable(),
                TextColumn::make('year.name'),
                TextColumn::make('subscriptionMonths.month.name')
                    ->label('Months')
                    ->badge(),
                TextColumn::make('paymentAccount.name'),
                TextColumn::make('price'),
                SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approve' => 'Approve',
                        'cancel' => 'Cancel',
                    ])
                    ->selectablePlaceholder(false),
                    // ->disableOptionWhen(function ( $record) {
                    //     return $record['status'] == 'approve';// Disable if approved
                    // }),
                TextColumn::make('created_at')
                        ->date()
                        ->sortable()
            ])
            ->filters([
                SelectFilter::make('diocese')
                    ->relationship('user.diocese','name')
                    ->label('Select the diocese')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Info')
                    ->schema([
                        TextEntry::make('year.name')->label('Subscription Year'),
                        TextEntry::make('subscriptionMonths.month.name')->label('Subscription Month'),
                        TextEntry::make('paymentAccount.name')->label('Payment'),
                        TextEntry::make('price'),
                        TextEntry::make('status'),
                        TextEntry::make('created_at')
                            ->date()
                ])->columns(3),
                Section::make('User Info')
                    ->schema([
                        TextEntry::make('user.name')->label('User Name'),
                        TextEntry::make('user.phone')->label('Phone Number'),
                        TextEntry::make('user.diocese.name')->label('Diocese'),
                        TextEntry::make('created_at')
                            ->label('Joined at')
                            ->date()
                ])->columns(4),
                Section::make('Slip Info')
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('slip')
                            ->collection('payment-slip')
                            ->width('100%')
                            ->height('auto')
                ])->columns(1),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SubscriptionMonthsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
            'view' => Pages\ViewSubscription::route('/{record}'),
        ];
    }
}
