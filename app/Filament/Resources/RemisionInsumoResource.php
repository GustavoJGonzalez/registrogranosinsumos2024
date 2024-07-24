<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RemisionInsumoResource\Pages;
use App\Filament\Resources\RemisionInsumoResource\RelationManagers;
use App\Models\RemisionInsumo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;

class RemisionInsumoResource extends Resource
{
    protected static ?string $model = RemisionInsumo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'EMBARQUE';
    protected static ?string $navigationLabel = 'Remision de Insumos'; 


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            
                Forms\Components\Select::make('empresas_id')->label('Emisor')
                ->required()->relationship('empresas', 'nombre'),
                Forms\Components\Select::make('empresa_clientes_id')
                ->required()->relationship('empresas_clientes', 'nombre')->label('Razon Social'),
                Forms\Components\Select::make('insumos_id')
                ->required()->relationship('insumos', 'nombre'),
                //Forms\Components\Select::make('medidas_id')
                //->required()->relationship('medidas', 'nombre'),
                Forms\Components\TextInput::make('numeroRemision')
                ->required()
                ->maxLength(255),
               
                Forms\Components\TextInput::make('cedula_chofer')
                ->required()
                ->label('Cédula del Chofer')
                ->maxLength(255)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $chofer = Chofer::findByCI($state);
                    if ($chofer) {
                        $set('chofers_id', $chofer->id);
                        $set('ci', $chofer->ci);
                        $set('celular', $chofer->celular);
                        $set('domicilio', $chofer->domicilio);
                    }
                }),
              
               Forms\Components\Select::make('chofers_id')
               // ->required()
               ->relationship('chofers', 'chofer')
               ->reactive()
               ->afterStateUpdated(function ($state, callable $set) {
                $chofer = Chofer::find($state);
                if ($chofer) {
                    $set('ci', $chofer->ci);
                    $set('celular', $chofer->celular);
                    $set('domicilio', $chofer->domicilio);
                }
            })

    
               ->createOptionForm([
                Forms\Components\TextInput::make('chofer')
                    ->required()
                    ->label('Nombre y Apellido del Chofer')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ci')
                    ->required()
                    ->label('Numero de Cedula')
                    ->maxLength(255)
                    ->unique('chofers', 'ci'), // Esto asegura que el campo es único en la tabla chofers.
                   
                Forms\Components\TextInput::make('celular')
                    ->required()
                    ->label('Numero de celular del Chofer')
                    ->maxLength(255),
                Forms\Components\TextInput::make('domicilio')
                    ->required()
                    ->label('Domicilio del Chofer')
                    ->maxLength(255),
                
                
            ]),
                Forms\Components\TextInput::make('ci')->required(),
                Forms\Components\TextInput::make('celular')->required(),
                Forms\Components\TextInput::make('domicilio')->required(),
                Forms\Components\TextInput::make('pesoBruto')
                //->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $pesoBruto = $get('pesoBruto'); 
                    $pesoTara = $get('pesoTara'); 
                    if ($pesoBruto !== null && $pesoTara !== null) {
                        $pesoNeto = $pesoBruto - $pesoTara;
                        $set('pesoNeto', $pesoNeto);
                    }
                })
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('pesoTara')
                ->reactive()
               // ->default(0)
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $pesoBruto = intval($get('pesoBruto')); // Convertir a entero explícitamente
                    $pesoTara = intval($get('pesoTara')); // Convertir a entero explícitamente
                    if ($pesoBruto !== null && $pesoTara !== null) {
                        $pesoNeto = $pesoBruto - $pesoTara;
                        $set('pesoNeto', $pesoNeto);
                    }
                })
                //->required()
                ->maxLength(255),
    
                Forms\Components\TextInput::make('pesoNeto')
                ->readonly()
                //->disabled()
                //->required()
                ->maxLength(255),
    
    
             
    
                Forms\Components\TextInput::make('chapaCamion')
                ->required()
                ->maxLength(255),
    
                Forms\Components\TextInput::make('chapaSemi')
                ->required()
                ->maxLength(255),
                Forms\Components\DatePicker::make('fecha_registro')
                ->required()->default(fn () => now())
                ->readonly(),
    
                Forms\Components\TimePicker::make('hora_registro')
                ->required()->default(fn () => now())
                ->readonly(),
    
    
    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
           
            Tables\Columns\TextColumn::make('empresas.nombre')->label('EMISOR')->alignCenter(), // Centro el contenido de la columna
            Tables\Columns\TextColumn::make('empresas_clientes.nombre')->label('RAZON SOCIAL')->alignCenter(), // Centro el contenido de la columna
            Tables\Columns\TextColumn::make('insumos.nombre')->label('INSUMO')->alignCenter(), // Centro el contenido de la columna
            Tables\Columns\TextColumn::make('chofer')->label('CHOFER')->alignCenter(), // Centro el contenido de la columna
            Tables\Columns\TextColumn::make('pesoBruto')->label('PESO BRUTO(KG)')->alignCenter() // Centro el contenido de la columna,
            ->formatStateUsing(function ($state) {
                return number_format($state, 0, ',', ','); // Formatear con separador de miles
            }),
            Tables\Columns\TextColumn::make('pesoTara')->label('PESO TARA(KG)')->alignCenter() // Centro el contenido de la columna,
            ->formatStateUsing(function ($state) {
                return number_format($state, 0, ',', ','); // Formatear con separador de miles
            }),
            Tables\Columns\TextColumn::make('pesoNeto')->label('PESO NETO(KG)'),
            TextColumn::make('pesoNeto')
            ->label('PESO NETO')
            ->summarize(Sum::make())
            ->sortable()
            ->searchable()->alignCenter() // Centro el contenido de la columna,
            ->formatStateUsing(function ($state) {
                return number_format($state, 0, ',', ','); // Formatear con separador de miles
            }),
            Tables\Columns\TextColumn::make('numeroRemision')->label('N~ REMISION')->alignCenter(), // Centro el contenido de la columna
            Tables\Columns\TextColumn::make('chapaCamion')->label('CHAPA CAMION')->alignCenter(), // Centro el contenido de la columna
            Tables\Columns\TextColumn::make('chapaSemi')->label('CHAPA SEMI')
            ->toggleable(isToggledHiddenByDefault: true)->alignCenter(), // Centro el contenido de la columna
            
            Tables\Columns\TextColumn::make('fecha_registro')
            ->date()->label('FECHA')->alignCenter(), // Centro el contenido de la columna
            Tables\Columns\TextColumn::make('hora_registro')
            ->time()->label('HORA')->alignCenter(), // Centro el contenido de la columna
            ])
            ->filters([
               // SelectFilter::make('Razon Social')->relationship('empresas', 'nombre'),
               // SelectFilter::make('EmpresaCliente')->relationship('empresas_clientes', 'nombre') ->label('Emisor'),
               // SelectFilter::make('Insumos')->relationship('Insumos', 'nombre'),
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
            'index' => Pages\ListRemisionInsumos::route('/'),
            'create' => Pages\CreateRemisionInsumo::route('/create'),
            'view' => Pages\ViewRemisionInsumo::route('/{record}'),
            'edit' => Pages\EditRemisionInsumo::route('/{record}/edit'),
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
