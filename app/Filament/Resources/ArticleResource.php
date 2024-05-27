<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Models\Category;
use App\Models\Month;
use App\Models\Year;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Catogories';

    protected static ?int $navigationSort = 4;

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
                    ->live()
                    ->preload()
                    ->options(function (Get $get) {
                        $yearId = $get('year_id');
                        if ($yearId) {
                            return Month::where('year_id', $yearId)->pluck('name', 'id');
                        }
                        return []; // Return empty options if no grade selected
                    })
                    ->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->options(function (Get $get){
                        $monthId = $get('month_id');
                        $yearId = $get('year_id');
                        if ($monthId) {
                            return Category::where('year_id', $yearId)->where('month_id', $monthId)->pluck('name', 'id');
                        }
                        return [];
                    })
                    ->searchable()
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                SpatieMediaLibraryFileUpload::make('cover image')
                    ->collection('article-image')
                    ->conversion('thumb')
                    ->nullable()
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    SpatieMediaLibraryImageColumn::make('Cover')
                        ->collection('article-image')
                        ->defaultImageUrl(asset('assets/images/default.png'))
                        ->conversion('thumb'),
                    TextColumn::make('title'),
                    TextColumn::make('year.name'),
                    TextColumn::make('month.name'),
                    TextColumn::make('category.name'),
                ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
                        TextEntry::make('year.name')->label('Year'),
                        TextEntry::make('month.name')->label('Month'),
                        TextEntry::make('category.name')->label('Category'),
                        TextEntry::make('status')
                ])->columns(3),
                Section::make('Article')
                    ->schema([
                        TextEntry::make('title'),
                        SpatieMediaLibraryImageEntry::make('cover image')
                            ->collection('article-image')
                            ->width('10%')
                            ->height('auto')
                ])->columns(1),
                Section::make('Content')
                    ->schema([
                        TextEntry::make('content'),
                ])->columns(1),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
            // 'view' => Pages\ViewArticle::route('/{record}'),
        ];
    }
}
