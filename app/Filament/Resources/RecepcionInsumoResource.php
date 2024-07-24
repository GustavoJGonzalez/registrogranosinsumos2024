<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecepcionInsumoResource\Pages;
use App\Filament\Resources\RecepcionInsumoResource\RelationManagers;
use App\Models\RecepcionInsumo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;


use Filament\Tables\Columns\ComputedColumn;
use Filament\Forms\Components\StaticText;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\Filter;

class RecepcionInsumoResource extends Resource
{
    protected static ?string $model = RecepcionInsumo::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'RECEPCIONES';
    protected static ?string $navigationLabel = 'Recepcion de Insumos'; 

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
          
            Forms\Components\Select::make('empresa_clientes_id')
            ->required()->relationship('empresas_clientes', 'nombre')->label('Emisor'),

            Forms\Components\Select::make('empresas_id')->label('Razon Social')
            ->required()->relationship('empresas', 'nombre'),
            Forms\Components\Select::make('insumos_id')
            ->required()->relationship('insumos', 'nombre'),
            //Forms\Components\Select::make('medidas_id')
            //->required()->relationship('medidas', 'nombre'),
            Forms\Components\TextInput::make('numeroRemision')
            ->required()
            ->maxLength(255),
            Forms\Components\TextInput::make('chofer')
            ->required()
            ->maxLength(255),
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
            Tables\Columns\TextColumn::make('empresas_clientes.nombre')->label('EMISOR')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('empresas.nombre')->label('RAZON SOCIAL')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('insumos.nombre')->label('INSUMO')->alignCenter(), // Centro el contenido de la columna,
            //Tables\Columns\TextColumn::make('medidas.nombre')->label('MEDIDA'),
            
           
            Tables\Columns\TextColumn::make('chofer')->label('CHOFER')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('pesoBruto')->label('PESO BRUTO(KG)')->alignCenter() // Centro el contenido de la columna,
            ->formatStateUsing(function ($state) {
                return number_format($state, 0, ',', ','); // Formatear con separador de miles
            }),

            Tables\Columns\TextColumn::make('pesoTara')->label('PESO TARA(KG)')->alignCenter() // Centro el contenido de la columna,
            ->formatStateUsing(function ($state) {
                return number_format($state, 0, ',', ','); // Formatear con separador de miles
            }),
            Tables\Columns\TextColumn::make('pesoNeto')->label('PESO NETO(KG)')->alignCenter(), // Centro el contenido de la columna,
           
            TextColumn::make('pesoNeto')
            ->label('PESO NETO(KG)')
            ->summarize(Sum::make())
            ->sortable()
            ->searchable()->alignCenter() // Centro el contenido de la columna,
            ->formatStateUsing(function ($state) {
                return number_format($state, 0, ',', ','); // Formatear con separador de miles
            }),
            Tables\Columns\TextColumn::make('numeroRemision')->label('N~ REMISION')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('chapaCamion')->label('CHAPA CAMION')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('chapaSemi')->label('CHAPA SEMI')
            ->toggleable(isToggledHiddenByDefault: true)->alignCenter(), // Centro el contenido de la columna,
            
            Tables\Columns\TextColumn::make('fecha_registro')
            ->date()->label('FECHA')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('hora_registro')
            ->time()->label('HORA')->alignCenter(), // Centro el contenido de la columna,
        ])
            ->filters([
                
                SelectFilter::make('Razon Social')->relationship('empresas', 'nombre'),
                SelectFilter::make('EmpresaCliente')->relationship('empresas_clientes', 'nombre') ->label('Emisor'),
                SelectFilter::make('Insumos')->relationship('Insumos', 'nombre'),
                Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('created_from')->label('Fecha Inicio'),
                    Forms\Components\DatePicker::make('created_until')->label('Fecha Fin'),
                    
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),
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
            'index' => Pages\ListRecepcionInsumos::route('/'),
            'create' => Pages\CreateRecepcionInsumo::route('/create'),
            'view' => Pages\ViewRecepcionInsumo::route('/{record}'),
            'edit' => Pages\EditRecepcionInsumo::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
         // dd(auth()->user()->id);
         if (!auth()->user()->hasRole('super_admin')) {
            return static::getModel()::query()->whereHas('empresas', function ($query) {
                $query->where('empresas_id', auth()->user()->empresas->pluck('id'));
            });
        }



        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
