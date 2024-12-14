<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeServiceResource\Pages;
use App\Filament\Resources\HomeServiceResource\RelationManagers;
use App\Filament\Resources\HomeServiceResource\RelationManagers\TestimonialsRelationManager;
use App\Models\HomeService;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomeServiceResource extends Resource
{
    protected static ?string $model = HomeService::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'Product';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('Details')
            ->schema([
                TextInput::make('name')->maxLength(255)->required(),
                FileUpload::make('thumbnail')->required()->image(),
                TextInput::make('price')->required()->numeric()->prefix('IDR')
                ->mask(RawJs::make('$money($input)'))->stripCharacters(','),
                TextInput::make('duration')->required()->numeric()->prefix('Hours'),
            ]),
            Fieldset::make('Aditionals')
            ->schema([
                Repeater::make('benefits')
                ->relationship('benefits')
                ->schema([
                    TextInput::make('name')->required(),
                ]),
                Textarea::make('about')->required(),
                Select::make('is_popular')->options([
                    true => 'Popular',
                    false => 'Not Popular',
                ]),
                Select::make('category_id')
                ->relationship('category','name')
                ->searchable()
                ->preload()
                ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('category.name'),
                IconColumn::make('is_popular')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label('Popular'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category','name'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TestimonialsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomeServices::route('/'),
            'create' => Pages\CreateHomeService::route('/create'),
            'edit' => Pages\EditHomeService::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
