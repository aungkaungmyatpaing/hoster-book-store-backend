<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\Month;
use App\Models\Year;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Catogories';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('year_id')
                ->label('Year')
                ->preload()
                ->options(
                     Year::all()->pluck('name','id')
                )
                ->live()
                ->required(),
            Select::make('month_id')
                ->label('Month')
                ->preload()
                ->options(function (Get $get) {
                    $yearId = $get('year_id');
                    if ($yearId) {
                        return Month::where('year_id', $yearId)->pluck('name', 'id');
                    }
                    return []; // Return empty options if no grade selected
                })
                ->required(),
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year.name'),
                TextColumn::make('month.name'),
                TextColumn::make('name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->action(function ($data, $record){

                    if ($record->articles()->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Month is in use')
                            ->body('The Month is in use by article')
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->success()
                        ->title('Month deleted')
                        ->body('The Month data has been deleted')
                        ->send();

                    $record->delete();
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
