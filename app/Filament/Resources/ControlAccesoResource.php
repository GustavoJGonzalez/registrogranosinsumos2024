<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ControlAccesoResource\Pages;
use App\Filament\Resources\ControlAccesoResource\RelationManagers;
use App\Models\ControlAcceso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use App\Models\Chofer;
//use Livewire\Component;
use Livewire\Livewire; // Asegúrate de importar Livewire aquí
class ControlAccesoResource extends Resource
{
    protected static ?string $model = ControlAcceso::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'CONTROL PORTERIA';
    protected static ?string $navigationLabel = 'Control de Camiones'; 

    public static function form(Form $form): Form
    {
        Log::info('ControlAccesoResource::form ejecutado');

        return $form
            ->schema([
               // Livewire::component('control-acceso-form', \App\Http\Livewire\ControlAccesoForm::class),
                Forms\Components\Select::make('operacion')
                ->options([
                    'REMISION' => 'Remisión',
                    'RECEPCION' => 'Recepción',
                ])
               // ->required()
                ->label('Razon de Ingreso')
                ->reactive(),


                   // Campos adicionales para 'remision'
                Forms\Components\Select::make('empresas_id')
                    ->label('Emisor')
                    ->relationship('empresas', 'nombre')
                    ->visible(fn ($get) => $get('operacion') === 'REMISION')
                    ->required(fn ($get) => $get('operacion') === 'REMISION'),

                Forms\Components\Select::make('empresa_clientes_id')
                    ->label('Destinatario')
                    ->relationship('empresas_clientes', 'nombre')
                    ->visible(fn ($get) => $get('operacion') === 'REMISION')
                    ->required(fn ($get) => $get('operacion') === 'REMISION'),



                   // Campos adicionales para 'recepcion'

                Forms\Components\Select::make('empresa_clientes_id')
                   ->label('Emisor')
                   ->relationship('empresas_clientes', 'nombre')
                   ->visible(fn ($get) => $get('operacion') === 'RECEPCION')
                   ->required(fn ($get) => $get('operacion') === 'RECEPCION'),
                Forms\Components\Select::make('empresas_id')
                   ->label('Razon Social')
                   ->relationship('empresas', 'nombre')
                   ->visible(fn ($get) => $get('operacion') === 'RECEPCION')
                  ->required(fn ($get) => $get('operacion') === 'RECEPCION'),

              //Selecionar mercaderia si es granos o insumos

                Forms\Components\Select::make('productos')
                   ->options([
                       'productos' => 'Granos',
                       'insumos' => 'Insumos',
                   ])
                   ->required()
                   ->label('Mecaderia')
                   ->reactive(),

                 // Campos adicionales para granos

                 Forms\Components\Select::make('productos_id')
                 ->label('Seleciona Granos')
                 ->relationship('productos', 'nombre')
                 ->visible(fn ($get) => $get('productos') === 'productos')
                 ->required(fn ($get) => $get('productos') === 'productos'),

                // Campos adicionales para granos

                Forms\Components\Select::make('insumos_id')
                ->label('Seleciona Insumo')
                ->relationship('insumos', 'nombre')
                ->visible(fn ($get) => $get('productos') === 'insumos')
                ->required(fn ($get) => $get('productos') === 'insumos'),


                //Este codigo sirve para ingresar el numero de cedula del chofer y que cargue los datos del chofer el nombre, ci y el numero de cedula

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
                Forms\Components\TextInput::make('chapaCamion')->required(),
               
                Forms\Components\TextInput::make('chapaSemi')->required(),

                //Forms\Components\TextInput::make('colorCamion')->required(),

                Forms\Components\Select::make('colorCamion')
                ->options([
                    'Negro' => 'Negro',
                    'Blanco' => 'Blanco',
                    'Rojo' => 'Rojo',
                    'Azul' => 'Azul',
                    'Amarillo' => 'Amarillo',
                    'Verde' => 'Verde',

                    


                ]),
               

                Forms\Components\Select::make('colorSemi')->required()
                ->options([
                    'Negro' => 'Negro',
                    'Blanco' => 'Blanco',
                    'Rojo' => 'Rojo',
                    'Azul' => 'Azul',
                    'Amarillo' => 'Amarillo',
                    'Verde' => 'Verde',

                    


                ]),
               
                Forms\Components\Select::make('ejesCamion')->required()
                ->options([
                    '2 Ejes' => '2 Ejes',
                    '3 Ejes' => '3 Ejes',
                   
                    


                ]),
                
                Forms\Components\Select::make('ejesSemi')->required()
                ->options([
                    '2 Ejes' => 'O OO 3 Ejes Separado',
                    '3 Ejes' => 'OOO  3 Ejes',
                   
                    


                ]),
                
                Forms\Components\Select::make('transportadoras_id')
                ->required()->relationship('transportadoras', 'nombre'),
               
                Forms\Components\DatePicker::make('fecha_ingreso')->required()
                ->default(fn () => now()),
                Forms\Components\TimePicker::make('hora_ingreso')->required()
                ->default(fn () => now()),
                Forms\Components\DatePicker::make('fecha_salida'),
                Forms\Components\TimePicker::make('hora_salida'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('operacion')->label('OPERACIONES')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('empresas.nombre')->label('EMPRESA')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('empresas_clientes.nombre')->label('DESTINARIO')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('productos.nombre')->label('PRODUCTOS')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('chofers.chofer')->label('Chofer')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('ci')->label('C.I.')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('celular')->label('Celular')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('domicilio')->label('Domicilio')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('chapaCamion')->label('Chapa Camion')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('colorCamion')->label('Color Camion')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('ejesCamion')->label('Ejes Camion')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('chapaSemi')->label('Chapa Semi')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('colorSemi')->label('Color Semi')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('ejesSemi')->label('Ejes Semi')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('transportadoras.nombre')->label('Transportadora')
                ->alignCenter(), // Centro el contenido de la columna

                Tables\Columns\TextColumn::make('fecha_ingreso')->date()->label('Fecha Ingreso')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('hora_ingreso')->time()->label('Hora Ingreso')
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('fecha_salida')->date()->label('Fecha Salida')->toggleable()
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('hora_salida')->time()->label('Hora Salida')->toggleable()
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\BooleanColumn::make('salida_registrada')->label('Salida Registrada')
                ->alignCenter() // Centro el contenido de la columna
                    ->getStateUsing(fn ($record) => $record->fecha_salida !== null && $record->hora_salida !== null),
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
                Tables\Actions\Action::make('registrar_salida')
                    ->label('Registrar Salida')
                    ->action(function ($record) {
                        $record->update([
                            'fecha_salida' => now()->toDateString(),
                            'hora_salida' => now()->toTimeString(),
                        ]);
                    })
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->hidden(fn ($record) => $record->fecha_salida !== null && $record->hora_salida !== null),
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
protected function getFormSchema(): array
    {
        return [
            Forms\Components\Placeholder::make('ChoferSearch')
                ->content(function () {
                    return view('livewire.chofer-search');
                }),
            // Otros campos del formulario...
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListControlAccesos::route('/'),
            'create' => Pages\CreateControlAcceso::route('/create'),
            'view' => Pages\ViewControlAcceso::route('/{record}'),
            'edit' => Pages\EditControlAcceso::route('/{record}/edit'),
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
