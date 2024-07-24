<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecepcionResource\Pages;
use App\Filament\Resources\RecepcionResource\RelationManagers;

use App\Filament\Resources\RecepionResource\RelationManagers\EmpresaRelationManagers;

use App\Filament\Resources\RecepionResource\RelationManagers\UserRelationManagers;

use App\Models\Recepcion;
use App\Models\Empresa;
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

use Filament\Forms\Components\Button;



use Filament\Tables\Columns\ComputedColumn;
use Filament\Forms\Components\StaticText;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\Filter;

use Filament\Tables\ColumnValue\TextValue;
use App\Models\Remision;

use Filament\Forms\Components\TextInput;


use Illuminate\Support\Facades\Auth;

// Export
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

use Filament\Forms\Action;
use App\Models\Post;


use Filament\Forms\Components\Select;
use JasperPHP\JasperPHP;
use App\Models\Chofer;





class RecepcionResource extends Resource
{
    protected static ?string $model = Recepcion::class;
 

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'RECEPCIONES';
    protected static ?string $navigationLabel = 'Recepcion de Granos'; 

   

    public static function form(Form $form): Form
    {  
        
      // Action::make('updateRecepcion')
        //->fillForm(fn (Recepcion $record): array => [
          //  'empresas_id' => $record->empresas_id,
            //'zafras_id' => $record->zafras_id,
            //'productos_id' => $record->productos_id,
            // ... (otras columnas)
        //]);

        
        
     
        
        return $form
      

        ->schema([
            Forms\Components\Select::make('empresas_id')
            ->required()->relationship('empresas', 'nombre')

            
            ->createOptionForm([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre'),
                Forms\Components\TextInput::make('ruc')
                    ->required()
                    ->tel()
                    ->label('Ruc')
                    ->maxLength(8),

                Forms\Components\TextInput::make('direccion')
                    ->required()
                    ->maxLength(255)
                    ->label('Direccion'),
                Forms\Components\DatePicker::make('fecha_registro')
                    ->required()
                    ->label('Fecha'),
            ]),

          //  Forms\Components\TextInput::make('role'),


            Forms\Components\Select::make('zafras_id')
            ->required()->relationship('zafras', 'año')
            ->createOptionForm([
                Forms\Components\TextInput::make('año')
                    ->required()
                    ->label('Years Zafra')
                    ->maxLength(255),
                
            ]),



            Forms\Components\Select::make('productos_id')
            ->relationship('productos', 'nombre')
            //->searchable()
           
             
            ->createOptionForm([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->label('Producto')
                    ->maxLength(255),
                
            ]),
            
            Forms\Components\Select::make('transportadoras_id')
            ->required()->relationship('transportadoras', 'nombre'),


            Forms\Components\Select::make('parcelas_id')
           // ->required()
           ->relationship('parcelas', 'nombre')

           ->createOptionForm([
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->label('Nombre de la Parcela')
                ->maxLength(255),
            Forms\Components\TextInput::make('hectarea')
                ->required()
                ->label('Cantidad de Hectareas')
                ->maxLength(255),
            
        ]),





            Forms\Components\Select::make('silos_id')
            //->required()
            ->relationship('silos', 'nombre')
            ->createOptionForm([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->label('Nombre del Silo')
                    ->maxLength(255),
                
            ]),

           
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
            ->minLength(2)
            ->maxLength(5),
            
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
            ->minLength(2)
            ->maxLength(5),



            Forms\Components\TextInput::make('pesoNeto')
            ->readonly()
            //->disabled()
            //->required()
            ->maxLength(255),

            Forms\Components\TextInput::make('chapaCamion')
            ->required()
            ->autocomplete()
            ->maxLength(255),

            Forms\Components\TextInput::make('chapaSemi')
            //->required()
            //->default(function () {
              //  $chofer = $this->resource()->getModel()->chofer ?? null;
               // if ($chofer) {
                //    $ultimoRegistro = Recepcion::where('chofer', $chofer)->latest()->first();
                //    return $ultimoRegistro ? $ultimoRegistro->chapaSemi : '';
                //}
                //return '';
            //})
            ->maxLength(255),

            Forms\Components\TextInput::make('humedad')
            //->required()
            ->maxLength(255),

            Forms\Components\TextInput::make('impureza')
            //->required()
            ->maxLength(255),

            Forms\Components\DatePicker::make('fecha_ingreso')
           // ->readonly()
            //->required()
            ->default(fn () => now()),
            //->disabled(),

            Forms\Components\TimePicker::make('hora_ingreso')
            //->readonly()
           //->required()
           ->default(fn () => now()),
           //->disabled(),
        ]);
        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('empresas.nombre')->label('EMPRESA')
                ->alignCenter(), // Centro el contenido de la columna

                Tables\Columns\TextColumn::make('productos.nombre')->label('PRODUCTOS') 
                ->alignCenter(), // Centro el contenido de la columna
                Tables\Columns\TextColumn::make('transportadoras.nombre')->label('TRANSPORTADORA')->alignCenter(), // Centro el contenido de la columna,
             

                Tables\Columns\TextColumn::make('chofers.chofer')->label('CHOFER')
                ->alignCenter() // Centro el contenido de la columna
                ->searchable(),

                Tables\Columns\TextColumn::make('pesoBruto')->label('PESO BRUTO(KG)')
                ->alignCenter() // Centro el contenido de la columna
                ->formatStateUsing(function ($state) {
                    return number_format($state, 0, ',', ','); // Formatear con separador de miles
                }),

                Tables\Columns\TextColumn::make('pesoTara')->label('PESO TARA(KG)')
                ->alignCenter() // Centro el contenido de la columna
                ->formatStateUsing(function ($state) {
                    return number_format($state, 0, ',', ','); // Formatear con separador de miles
                }),

                Tables\Columns\TextColumn::make('pesoNeto')->label('PESO NETO(KG)')
                ->alignCenter(), // Centro el contenido de la columna
                TextColumn::make('pesoNeto')
                ->label('PESO NETO(KG)')
                ->summarize(Sum::make())
                ->sortable()
                ->searchable()
                ->alignCenter() // Centro el contenido de la columna
                ->formatStateUsing(function ($state) {
                    return number_format($state, 0, ',', ','); // Formatear con separador de miles
                }),


                Tables\Columns\TextColumn::make('parcelas.nombre')->label('PARCELA')
                ->toggleable(isToggledHiddenByDefault: true)
                ->alignCenter(), // Centro el contenido de la columna

                Tables\Columns\TextColumn::make('silos.nombre')->label('SILO')
                ->toggleable(isToggledHiddenByDefault: true)
                ->alignCenter(), // Centro el contenido de la columna

                Tables\Columns\TextColumn::make('humedad')->label('HUMEDAD'),
                TextColumn::make('humedad')
                ->label('HUMEDAD H%')
                ->summarize(Average::make())
                ->alignCenter(), // Centro el contenido de la columna

                Tables\Columns\TextColumn::make('impureza')->label('IMPUREZA')
                ->toggleable(isToggledHiddenByDefault: true),




                Tables\Columns\TextColumn::make('chapaCamion')->label('CHAPA CAMION')
                ->toggleable(isToggledHiddenByDefault: true)
                ->alignCenter(), // Centro el contenido de la columna

                Tables\Columns\TextColumn::make('chapaSemi')->label('CHAPA SEMI')
                ->toggleable(isToggledHiddenByDefault: true)
                ->alignCenter(), // Centro el contenido de la columna

                Tables\Columns\TextColumn::make('zafras.año')->label('ZAFRA')
                ->toggleable(isToggledHiddenByDefault: true)
                ->alignCenter(), // Centro el contenido de la columna

                Tables\Columns\TextColumn::make('fecha_ingreso')
                ->date()->label('FECHA')
                ->alignCenter(), // Centro el contenido de la columna

                Tables\Columns\TextColumn::make('hora_ingreso')
                ->time()->label('HORA')
                ->alignCenter(), // Centro el contenido de la columna
            ])
            ->filters([

                SelectFilter::make('Empresa')->relationship('empresas', 'nombre'),
                SelectFilter::make('Producto')->relationship('productos', 'nombre'),
                SelectFilter::make('Parcela')->relationship('parcelas', 'nombre'),
                SelectFilter::make('Silo')->relationship('silos', 'nombre'),
                SelectFilter::make('Zafra')->relationship('zafras', 'año'),



                // ->options(
                //     Auth::user()->esJefe()
                //         ? Empresa::pluck('nombre', 'id') // Para jefes, muestra todas las empresas
                //         : Empresa::where('id', Auth::user()->empresas_id)->pluck('nombre', 'id') // Para clientes, muestra solo su empresa
                // )
                // ->default(
                //     Auth::user()->esUsuarioH3()
                //         ? null // No se establece un valor predeterminado para jefes
                //         : Auth::user()->empresas_id // Establecer la empresa del cliente como valor predeterminado
                // ),







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

                        // if (!Auth::user()->esJefe()) {
                        //     // Si es un cliente, solo mostrar las recepciones de su empresa
                        //     $query->where('empresas_id', Auth::user()->empresa_id);
                        // }





                }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('generateTicket')
                    ->label('Generar Ticket')
                    ->action(function ($record) {
                        return static::generateTicket($record);
                    })

  
        ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                    ExportBulkAction::make()
                 //  FilamentExportBulkAction::make('export'),
                ]),
            ]);





    }
    public static function generateTicket($record)
    {
        $jasper = new JasperPHP();
        $input = resource_path('reports/ticket.jasper');
        $output = storage_path('app/public/tickets/' . time() . '_ticket');

        $parameters = [
            'RECEPCION_ID' => $record->id,
            // Agrega otros parámetros si es necesario
        ];

        $jasper->process(
            $input,
            $output,
            ['pdf'],
            $parameters,
            [
                'driver' => 'mysql',
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'host' => env('DB_HOST'),
                'database' => env('DB_DATABASE'),
                'port' => env('DB_PORT')
            ]
        )->execute();

        return response()->download($output . '.pdf');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EmpresaRelationManager::class,
            RelationManagers\UserRelationManager::class,
        ];
    }

    protected static ?string $recordTitleAttribute = 'chofer';
    public static function getGloballySearchableAttributes(): array
    {
        return ['chofer'];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecepcions::route('/'),
            'create' => Pages\CreateRecepcion::route('/create'),
            'view' => Pages\ViewRecepcion::route('/{record}'),
            'edit' => Pages\EditRecepcion::route('/{record}/edit'),
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
