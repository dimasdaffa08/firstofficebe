<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use App\Models\BookingTransaction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->maxLength(255),

                TextInput::make('booking_trx_id')
                ->required()
                ->maxLength(255)
                ->label('Booking TransactionID'),

                TextInput::make('phone_number')
                ->required()
                ->maxLength(255)
                ->label('Phone Number'),

                Select::make('office_space_id')
                ->required()
                ->relationship('officeSpace', 'name')
                ->searchable()
                ->preload()
                ->label('Office Space'),

                TextInput::make('duration')
                ->required()
                ->numeric()
                ->suffix('Days'),

                Select::make('is_paid')
                ->required()
                ->options([
                    true => 'Paid',
                    false => 'Not Paid'
                ])
                ->label('Is Paid ?'),

                DatePicker::make('started_at')
                ->required()
                ->label("Started At"),

                DatePicker::make('ended_at')
                ->required()
                ->label('Ended At'),

                TextInput::make('total_amount')
                ->required()
                ->numeric()
                ->prefix('IDR')
                ->label('Total Amount'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_trx_id')
                ->label('Booking TransactionID')
                ->searchable(),

                TextColumn::make('name')
                ->searchable(),

                TextColumn::make('officeSpace.name'),

                TextColumn::make('officeSpace.city.name'),

                TextColumn::make('started_at')
                ->date(),

                TextColumn::make('ended_at')
                ->date(),

                IconColumn::make('is_paid')
                ->label("Is Paid ?")
                ->trueColor('success')
                ->falseColor('danger')
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
            ])
            ->filters([
                SelectFilter::make('is_paid')
                ->label('Is Paid ?')
                ->options([
                    true => 'Paid',
                    false => 'Not Paid'
                ]),

                SelectFilter::make("officeSpace.city.name")
                ->label('City')
                ->relationship('officeSpace.city', 'name'),
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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
