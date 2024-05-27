<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonthResource\Pages;
use App\Filament\Resources\MonthResource\RelationManagers;
use App\Models\Month;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Group as FormGroup;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class MonthResource extends Resource
{
    protected static ?string $model = Month::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Catogories';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                FormGroup::make()->schema([
                    Toggle::make('publish')
                    ->onColor('success')
                    ->offColor('danger'),
                ]),
                FormGroup::make()->schema([
                    Select::make('year_id')
                        ->relationship(name: 'year', titleAttribute: 'name')
                        ->required(),
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('mm_name')
                        ->required()
                        ->maxLength(255),
                    Select::make('month')
                        ->required()
                        ->searchable()
                        ->options([
                            1 => 'January',
                            2 => 'February',
                            3 => 'March',
                            4 => 'April',
                            5 => 'May',
                            6 => 'June',
                            7 => 'July',
                            8 => 'August',
                            9 => 'September',
                            10 => 'October',
                            11 => 'November',
                            12 => 'December',
                        ])


                ])->columns(2)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year.name')
                    ->sortable(),
                TextColumn::make('name'),
                TextColumn::make('mm_name'),
                ToggleColumn::make('publish')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->action(function ($data, $record){

                    if ($record->categories()->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Month is in use')
                            ->body('The Month is in use by category')
                            ->send();

                        return;
                    }

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
            'index' => Pages\ListMonths::route('/'),
            'create' => Pages\CreateMonth::route('/create'),
            'edit' => Pages\EditMonth::route('/{record}/edit'),
        ];
    }
}
