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
use App\Models\Chofer;

class RemisionResource extends Resource
{
    protected static ?string $model = Remision::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'EMBARQUE';
    protected static ?string $navigationLabel = 'Remision de Granos'; 

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

            Forms\Components\TextInput::make('cedula_chofer')
            //->required()
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
           ->label('Nombre y Apellido del Chofer')
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

            Forms\Components\DatePicker::make('fecha_ingreso')->required()
            ->default(fn () => now()),
            Forms\Components\TimePicker::make('hora_ingreso')->required()
            ->default(fn () => now()),

           // Forms\Components\DatePicker::make('fecha_registro')
           // ->readonly()
            //->required()
           // ->default(fn () => now()),
            //->disabled(),

            //Forms\Components\TimePicker::make('hora_registro')
            //->readonly()
           //->required()
           //->default(fn () => now()),
           //->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('empresas.nombre')->label('EMPRESA')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('empresas_clientes.nombre')->label('DESTINARIO')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('productos.nombre')->label('PRODUCTOS')->alignCenter(), // Centro el contenido de la columna,
            
            Tables\Columns\TextColumn::make('pesoTara')->label('PESO TARA(KG)')->alignCenter() // Centro el contenido de la columna,
            ->formatStateUsing(function ($state) {
                return number_format($state, 0, ',', ','); // Formatear con separador de miles
            }),
            Tables\Columns\TextColumn::make('pesoBruto')->label('PESO BRUTO(KG)')->alignCenter() // Centro el contenido de la columna,
            ->formatStateUsing(function ($state) {
                return number_format($state, 0, ',', ','); // Formatear con separador de miles
            }),
            Tables\Columns\TextColumn::make('pesoNeto')->label('PESO NETO(KG)'),
            
            TextColumn::make('pesoNeto')
            ->label('PESO NETO')->alignCenter() // Centro el contenido de la columna,
            ->summarize(Sum::make())
            ->sortable()
            ->searchable()
             ->formatStateUsing(function ($state) {
                return number_format($state, 0, ',', ','); // Formatear con separador de miles
            }),
            Tables\Columns\TextColumn::make('humedad')->label('HUMEDAD'),
            TextColumn::make('humedad')
                ->label('HUMEDAD H%')->alignCenter() // Centro el contenido de la columna,
                ->summarize(Average::make()),
                
            Tables\Columns\TextColumn::make('impureza')->label('IMPUREZA')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('transportadoras.nombre')->label('TRANSPORTADORA')->alignCenter(), // Centro el contenido de la columna,
             
          
            Tables\Columns\TextColumn::make('chofers.chofer')->label('CHOFER')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('chapaCamion')->label('CHAPA CAMION')->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('chapaSemi')->label('CHAPA SEMI')
            ->toggleable(isToggledHiddenByDefault: true)->alignCenter(), // Centro el contenido de la columna,
            Tables\Columns\TextColumn::make('zafras.año')->label('ZAFRA')
            ->toggleable(isToggledHiddenByDefault: true)->alignCenter(), // Centro el contenido de la columna,

            Tables\Columns\TextColumn::make('fecha_ingreso')
            ->date()->label('FECHA')->alignCenter(), // Centro el contenido de la columna,

            Tables\Columns\TextColumn::make('hora_ingreso')
            ->time()->label('HORA')->alignCenter(), // Centro el contenido de la columna,
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
