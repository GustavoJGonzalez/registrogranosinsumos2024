<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChoferResource\Pages;
use App\Filament\Resources\ChoferResource\RelationManagers;
use App\Models\Chofer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChoferResource extends Resource
{
    protected static ?string $model = Chofer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'REGISTRO DE CHOFER';
    protected static ?string $navigationLabel = 'Choferes'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Forms\Components\TextInput::make('chofer')
                    ->required()
                    ->maxLength(255)
                    ->afterStateUpdated(function ($state, $set) {
                        $set('chofer', strtoupper($state));
                    }),// este codigo sirve si se escribe en minuscula paso todos en mayuscula
                    
                Forms\Components\TextInput::make('ci')
                    ->required()
                    ->maxLength(255)
                    ->unique(Chofer::class, 'ci', ignoreRecord: true) // Validación de unicidad
                    ->afterStateUpdated(function ($state, $set) {
                        $set('ci', strtoupper($state));
                    }),// este codigo sirve si se escribe en minuscula paso todos en mayuscula
                Forms\Components\TextInput::make('celular')
                    ->required()
                    ->maxLength(255)
                    ->afterStateUpdated(function ($state, $set) {
                        $set('celular', strtoupper($state));
                    }),// este codigo sirve si se escribe en minuscula paso todos en mayuscula
                Forms\Components\TextInput::make('domicilio')
                    ->required()
                    ->maxLength(255)
                    ->afterStateUpdated(function ($state, $set) {
                        $set('domicilio', strtoupper($state));
                    }),// este codigo sirve si se escribe en minuscula paso todos en mayuscula
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('chofer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ci')
                    ->searchable(),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domicilio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChofers::route('/'),
            'create' => Pages\CreateChofer::route('/create'),
            'view' => Pages\ViewChofer::route('/{record}'),
            'edit' => Pages\EditChofer::route('/{record}/edit'),
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
