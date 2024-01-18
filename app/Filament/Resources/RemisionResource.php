<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RemisionResource\Pages;
use App\Filament\Resources\RemisionResource\RelationManagers;
use App\Models\Remision;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;


use Filament\Tables\Columns\ComputedColumn;
use Filament\Forms\Components\StaticText;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\Filter;

use Filament\Tables\ColumnValue\TextValue;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Count;
//use Illuminate\Database\Query\Builder;

class RemisionResource extends Resource
{
    protected static ?string $model = Remision::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'EMBARQUE';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('empresas_id')
            ->required()->relationship('empresas', 'nombre'),

           Forms\Components\Select::make('empresa_clientes_id')
            ->required()->relationship('empresas_clientes', 'nombre')->label('Destinarios'),

            Forms\Components\Select::make('zafras_id')
            ->required()->relationship('zafras', 'año'),

            Forms\Components\Select::make('productos_id')
            ->required()->relationship('productos', 'nombre'),

            Forms\Components\Select::make('transportadoras_id')
            ->required()->relationship('transportadoras', 'nombre'),

            Forms\Components\TextInput::make('chofer')
            ->required()
            ->maxLength(255),
            Forms\Components\TextInput::make('pesoTara')
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
            Forms\Components\TextInput::make('pesoBruto')
            ->reactive()
            //->placeholder('0')
            //->default(0)
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
            ->default(function () {
                // Lógica para obtener la última chapa de camión registrada
                $ultimoRegistro = Remision::latest()->first(); // Reemplaza Modelo con tu modelo real
                return $ultimoRegistro ? $ultimoRegistro->chapaCamion : '';
            })
            ->maxLength(255),

            Forms\Components\TextInput::make('chapaSemi')
            ->required()
            ->default(function () {
                // Lógica para obtener la última chapa del semi registrada
                $ultimoRegistro = Remision::latest()->first(); // Reemplaza Modelo con tu modelo real
                return $ultimoRegistro ? $ultimoRegistro->chapaSemi : '';
            })
            ->maxLength(255),

            Forms\Components\TextInput::make('humedad')
            //->required()
            ->maxLength(255),

            Forms\Components\TextInput::make('impureza')
            //->required()
            ->maxLength(255),

            Forms\Components\DatePicker::make('fecha_registro')
            ->readonly()
            //->required()
            ->default(fn () => now()),
            //->disabled(),

            Forms\Components\TimePicker::make('hora_registro')
            ->readonly()
           //->required()
           ->default(fn () => now()),
           //->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('empresas.nombre')->label('EMPRESA'),
            Tables\Columns\TextColumn::make('empresas_clientes.nombre')->label('DESTINARIO'),
            Tables\Columns\TextColumn::make('productos.nombre')->label('PRODUCTOS'),
            
            Tables\Columns\TextColumn::make('pesoTara')->label('PESO TARA'),
            Tables\Columns\TextColumn::make('pesoBruto')->label('PESO BRUTO'),
            Tables\Columns\TextColumn::make('pesoNeto')->label('PESO NETO'),
            TextColumn::make('pesoNeto')
            ->label('PESO NETO')
            ->summarize(Sum::make())
            ->sortable()
            ->searchable(),
            Tables\Columns\TextColumn::make('humedad')->label('HUMEDAD'),
            TextColumn::make('humedad')
                ->label('HUMEDAD H%')
                ->summarize(Average::make()),
                
            Tables\Columns\TextColumn::make('impureza')->label('IMPUREZA'),
            Tables\Columns\TextColumn::make('transportadoras.nombre')->label('TRANSPORTADORA'),
             
          
            Tables\Columns\TextColumn::make('chofer')->label('CHOFER'),
            Tables\Columns\TextColumn::make('chapaCamion')->label('CHAPA CAMION'),
            Tables\Columns\TextColumn::make('chapaSemi')->label('CHAPA SEMI')
            ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('zafras.año')->label('ZAFRA')
            ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('fecha_registro')
            ->date()->label('FECHA'),
            Tables\Columns\TextColumn::make('hora_registro')
            ->time()->label('HORA'),
        ])
            ->filters([
                SelectFilter::make('Zafra')->relationship('zafras', 'año')->label('Year Zafra'),
                SelectFilter::make('Empresa')->relationship('empresas', 'nombre')->label('Empresas'),
                SelectFilter::make('Producto')->relationship('productos', 'nombre')->label('Productos'),
                SelectFilter::make('EmpresaCliente')->relationship('empresas_clientes', 'nombre') ->label('Destinarios'),
                SelectFilter::make('Transportadora')->relationship('transportadoras', 'nombre')->label('Transportadoras'),
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
            'index' => Pages\ListRemisions::route('/'),
            'create' => Pages\CreateRemision::route('/create'),
            'view' => Pages\ViewRemision::route('/{record}'),
            'edit' => Pages\EditRemision::route('/{record}/edit'),
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
