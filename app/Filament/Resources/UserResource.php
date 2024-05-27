<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Diocese;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Group as FormGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Filament\Resources\Pages\CreateRecord;
use Filament\Pages\Page;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Rawilk\FilamentPasswordInput\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Accounts';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormGroup::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->rule(function () {
                            return function ($attribute, $value, $fail) {
                                if (str_starts_with($value, '095') && strlen($value) !== 9) {
                                    $fail($attribute . ' must be 9 characters long if it starts with "095".');
                                } elseif (!str_starts_with($value, '095') && strlen($value) !== 11) {
                                    $fail($attribute . ' must be 11 characters long if it does not start with "095".');
                                }
                            };
                        }),
                    TextInput::make('email')
                        ->email()
                        ->nullable()
                        ->maxLength(255),
                    Select::make('diocese_id')
                        ->label('Diocese')
                        ->options(
                            Diocese::all()->pluck('name','id')
                        )
                        ->required(),
                    Password::make('password')
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (Page $livewire) => $livewire instanceof CreateRecord)
                        ->minLength(8),
                    // Password::make('password')
                    //     ->minLength(8),
                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->collection('user-profile')
                        ->conversion('thumb')
                        ->nullable(),
                ])
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('Avatar')
                    ->collection('user-profile')
                    ->defaultImageUrl(asset('assets/images/default.png'))
                    ->conversion('thumb'),
                TextColumn::make('diocese.name')
                    ->sortable(),
                TextColumn::make('name'),
                TextColumn::make('phone'),
                ToggleColumn::make('ban'),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('diocese')
                    ->relationship('diocese','name')
                    ->label('Select the diocese')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->action(function ($data, $record){
                    if ($record->histories()->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title('User is in use')
                            ->body('The User is in use by history')
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->success()
                        ->title('User deleted')
                        ->body('The User data has been deleted')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
