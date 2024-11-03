<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficeSpaceResource\Pages;
use App\Filament\Resources\OfficeSpaceResource\RelationManagers;
use App\Models\OfficeSpace;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OfficeSpaceResource extends Resource
{
    protected static ?string $model = OfficeSpace::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->maxLength(255),

                TextInput::make('address')
                ->required()
                ->maxLength(255),

                FileUpload::make('thumbnail')
                ->required()
                ->image()
                ->disk('public')
                ->directory('images/thumbnails'),

                Textarea::make('about')
                ->required()
                ->rows(10)
                ->cols(20),

                Repeater::make('photos')
                ->relationship('photos')
                ->schema([
                    FileUpload::make('photo')
                    ->required()
                    ->image()
                    ->disk('public')
                    ->directory('images/offices'),
                ]),

                Repeater::make('benefits')
                ->relationship('benefits')
                ->schema([
                    TextInput::make('name')
                    ->required()
                ]),

                Select::make('city_id')
                ->required()
                ->relationship('city', 'name')
                ->searchable()
                ->preload(),

                TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('IDR'),

                TextInput::make('duration')
                ->required()
                ->numeric()
                ->suffix("Days"),

                Select::make('is_open')
                ->required()
                ->options([
                    true => 'Open',
                    false => 'Closed'
                ]),

                Select::make('is_full_booked')
                ->required()
                ->options([
                    true => 'Not Availbale',
                    false => 'Available'
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable(),

                ImageColumn::make('thumbnail'),

                TextColumn::make('city.name'),

                IconColumn::make('is_full_booked')
                ->boolean()
                ->trueColor('danger')
                ->falseColor("success")
                ->trueIcon('heroicon-o-x-circle')
                ->falseIcon('heroicon-o-check-circle')
                ->label('Available'),
            ])
            ->filters([
                SelectFilter::make('city_id')
                ->label('City')
                ->relationship('city', 'name'),

                SelectFilter::make('is_full_booked')
                ->label('Available')
                ->options([
                    true => 'Not Availbale',
                    false => 'Available'
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOfficeSpaces::route('/'),
            'create' => Pages\CreateOfficeSpace::route('/create'),
            'edit' => Pages\EditOfficeSpace::route('/{record}/edit'),
        ];
    }
}
