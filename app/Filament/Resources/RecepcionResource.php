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







class RecepcionResource extends Resource
{
    protected static ?string $model = Recepcion::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'RECEPCIONES';

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
            ->required()->relationship('empresas', 'nombre'),


          //  Forms\Components\TextInput::make('role'),


            Forms\Components\Select::make('zafras_id')
            ->required()->relationship('zafras', 'año'),

            Forms\Components\Select::make('productos_id')
            ->required()
            ->relationship('productos', 'nombre'),

            Forms\Components\Select::make('parcelas_id')
           // ->required()
           ->relationship('parcelas', 'nombre'),

            Forms\Components\Select::make('silos_id')
            //->required()
            ->relationship('silos', 'nombre'),

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

            Forms\Components\DatePicker::make('fecha_registro')
           // ->readonly()
            //->required()
            ->default(fn () => now()),
            //->disabled(),

            Forms\Components\TimePicker::make('hora_registro')
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
                Tables\Columns\TextColumn::make('empresas.nombre')->label('EMPRESA'),

                Tables\Columns\TextColumn::make('productos.nombre')->label('PRODUCTOS'),

                Tables\Columns\TextColumn::make('chofer')->label('CHOFER'),
                Tables\Columns\TextColumn::make('pesoBruto')->label('PESO BRUTO'),
                Tables\Columns\TextColumn::make('pesoTara')->label('PESO TARA'),
                Tables\Columns\TextColumn::make('pesoNeto')->label('PESO NETO'),
                TextColumn::make('pesoNeto')
                ->label('PESO NETO')
                ->summarize(Sum::make())
                ->sortable()
                ->searchable(),


                Tables\Columns\TextColumn::make('parcelas.nombre')->label('PARCELA')
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('silos.nombre')->label('SILO')
                ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('humedad')->label('HUMEDAD'),
                TextColumn::make('humedad')
                ->label('HUMEDAD H%')
                ->summarize(Average::make()),
                Tables\Columns\TextColumn::make('impureza')->label('IMPUREZA')
                ->toggleable(isToggledHiddenByDefault: true),




                Tables\Columns\TextColumn::make('chapaCamion')->label('CHAPA CAMION')
                ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\EmpresaRelationManager::class,
            RelationManagers\UserRelationManager::class,
        ];
    }

    protected static ?string $recordTitleAttribute = 'empresas_id';
    public static function getGloballySearchableAttributes(): array
    {
        return ['empresas_id'];
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
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
