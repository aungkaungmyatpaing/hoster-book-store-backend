<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YearResource\Pages;
use App\Filament\Resources\YearResource\RelationManagers;
use App\Models\Year;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class YearResource extends Resource
{
    protected static ?string $model = Year::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Catogories';

    public static function form(Form $form): Form
    {
        $years = [];

        for ($year = 1999; $year <= 2100; $year++) {
            $years[$year] = (string) $year; // Cast year to string for consistency
        }

        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('mm_name')
                    ->required()
                    ->maxLength(255),
                Select::make('year')
                    ->required()
                    ->searchable()
                    ->options($years)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('mm_name'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->action(function ($data, $record){
                    if ($record->months()->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Year is in use')
                            ->body('The Year is in use by month')
                            ->send();

                        return;
                    }

                    if ($record->categories()->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Year is in use')
                            ->body('The Year is in use by category')
                            ->send();

                        return;
                    }

                    if ($record->articles()->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Year is in use')
                            ->body('The Year is in use by article')
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->success()
                        ->title('Year deleted')
                        ->body('The Year data has been deleted')
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
            'index' => Pages\ListYears::route('/'),
            'create' => Pages\CreateYear::route('/create'),
            'edit' => Pages\EditYear::route('/{record}/edit'),
        ];
    }
}
