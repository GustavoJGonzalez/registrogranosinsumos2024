<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecepcionGranosResource\Pages;
use App\Filament\Resources\RecepcionGranosResource\RelationManagers;
use App\Models\RecepcionGranos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Wizard;
use Illuminate\Support\HtmlString;
use Livewire\Component;

use Filament\Forms\Components\TextInput;



class RecepcionGranosResource extends Resource
{
    protected static ?string $model = RecepcionGranos::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

        
        ->schema([
            Wizard::make([
                Wizard\Step::make('Primer paso')
                    ->schema([
                       
                        Forms\Components\Select::make('empresas_id')
                        ->required()->relationship('empresas', 'nombre'),

                        Forms\Components\Select::make('zafras_id')
                        ->required()->relationship('zafras', 'año'),

                        Forms\Components\Select::make('productos_id')
                        ->required()->relationship('productos', 'nombre'),

                        Forms\Components\TextInput::make('chofer')
                        ->required()
                        ->maxLength(255),
                        Forms\Components\TextInput::make('pesoBruto')
                        ->reactive()
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
                    ]),


                Wizard\Step::make('Segundo paso')
                    ->schema([
                      
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
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $humedad = floatval($get('humedad'));
                            $pesoNeto = floatval($get('pesoNeto'));
                    
                            // Realizar cálculos para el descuento de humedad
                            $descuentoHumedad = 0.0;
                            if ($humedad >= 14.0 && $humedad <= 18.0) {
                                $descuentoHumedad = (($humedad - 14.0) / 0.10) * 0.15;
                            } elseif ($humedad > 18.0 && $humedad <= 25.0) {
                                $descuentoHumedad = (($humedad - 18.0) / 0.10) * 0.20 + 0.60;
                            }
                    
                            // Calcular peso líquido considerando solo el descuento por humedad
                            $pesoLiquido = $pesoNeto - $descuentoHumedad;
                    
                            // Asignar el valor al campo 'pesoLiquido'
                            $set('pesoLiquido', $pesoLiquido);
                        })
                        ->required()
                        ->maxLength(255),
                    
                    


                        Forms\Components\TextInput::make('humedad')
                        //->required()
                        ->maxLength(255),
            
                        Forms\Components\TextInput::make('impureza')
                        ->required()
                        ->maxLength(255),


                      Forms\Components\TextInput::make('pesoLiquido')
                        ->readonly()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $javascript = <<<JS
                            <script>
                                // Obtener el valor de humedad y pesoNeto
                                const humedad = parseFloat(document.querySelector('input[name="humedad"]').value);
                                const pesoNeto = parseFloat(document.querySelector('input[name="pesoNeto"]').value);

                                // Realizar cálculos para el descuento de humedad
                                let descuentoHumedad = 0.0;
                                if (humedad >= 14.0 && humedad <= 18.0) {
                                    descuentoHumedad = ((humedad - 14.0) / 0.10) * 0.15;
                                } else if (humedad > 18.0 && humedad <= 25.0) {
                                    descuentoHumedad = ((humedad - 18.0) / 0.10) * 0.20 + 0.60;
                                }

                                // Calcular peso líquido considerando solo el descuento por humedad
                                const pesoLiquido = pesoNeto - descuentoHumedad;

                                // Asignar el valor calculado al campo 'pesoLiquido'
                                document.querySelector('input[name="pesoLiquido"]').value = pesoLiquido;
                            </script>
                            JS;

                            echo $javascript; // Imprimir el código JavaScript
                        })
                        ->required()
                        ->maxLength(255),

                    
                    
                    
                        
                        //Forms\Components\TextInput::make('pesoLiquido')
                        //->readonly()
                        //->default(0)
                        //->disabled()
                        //->required()
                        //->maxLength(255),
                   


                        Forms\Components\TextInput::make('chapaCamion')
                        ->required()
                        ->maxLength(255),
                         Forms\Components\TextInput::make('chapaSemi')
                        ->required()
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
                        
                                
                                ]),
              
                ])->startOnStep(1)->submitAction(new HtmlString('<button type="submit">Submit</button>'))->persistStepInQueryString('wizard-step'),
          
          
            


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('empresas_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zafras_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('productos_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('chofer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pesoBruto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pesoTara')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pesoNeto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('chapaCamion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('chapaSemi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('humedad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('impureza')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_registro')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hora_registro'),
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
            'index' => Pages\ListRecepcionGranos::route('/'),
            'create' => Pages\CreateRecepcionGranos::route('/create'),
            'view' => Pages\ViewRecepcionGranos::route('/{record}'),
            'edit' => Pages\EditRecepcionGranos::route('/{record}/edit'),
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
