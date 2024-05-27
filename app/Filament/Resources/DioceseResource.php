<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DioceseResource\Pages;
use App\Filament\Resources\DioceseResource\RelationManagers;
use App\Filament\Resources\DioceseResource\RelationManagers\UsersRelationManager;
use App\Models\Diocese;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DioceseResource extends Resource
{
    protected static ?string $model = Diocese::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    protected static ?string $navigationGroup = 'Accounts';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($data, $record){

                        if ($record->users()->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title('Diocese is in use')
                                ->body('The Diocese is in use by users')
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->success()
                            ->title('Diocese deleted')
                            ->body('The Diocese data has been deleted')
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
            UsersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDioceses::route('/'),
            'create' => Pages\CreateDiocese::route('/create'),
            'edit' => Pages\EditDiocese::route('/{record}/edit'),
        ];
    }
}
