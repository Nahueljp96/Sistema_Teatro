<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CursoVentaResource\Pages;
use App\Filament\Resources\CursoVentaResource\RelationManagers;
use App\Models\CursoVenta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class CursoVentaResource extends Resource
{
    

    protected static ?string $model = CursoVenta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public function tuHermana (){
        $preference_id = '17201799-4b619b2b-c796-4565-9ac6-cab7d4d30d59';

            // Realiza la consulta con el where
            $esto = CursoVenta::where('preference_id', $preference_id);

            // Registra la consulta SQL generada en los logs para depuración
            Log::debug($esto->toSql());
            Log::debug($esto->getBindings());

            // Obtén el primer resultado de la consulta
            $registro = $esto->first();

            if ($registro) {
                Log::info('Registro encontrado:', $registro->toArray());
            } else {
                Log::warning('No se encontró ningún registro con ese preferenceID.');
            }
    }

    public static function form(Form $form): Form

                
    {
        return $form
            ->schema([
                Forms\Components\Select::make('curso_id')
                ->relationship('curso', 'nombre') // Relación con el modelo Obra y muestra el título
                ->required()
                ->label('Curso'),
                Forms\Components\TextInput::make('comprador_email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nombre_comprador')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('estado_pago')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefono')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('preference_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('curso.nombre') // Relación con Obra para mostrar el título
                    ->label('Curso')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('comprador_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_comprador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preference_id')
                    ->searchable(),
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
                //
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
            'index' => Pages\ListCursoVentas::route('/'),
            'create' => Pages\CreateCursoVenta::route('/create'),
            'edit' => Pages\EditCursoVenta::route('/{record}/edit'),
        ];
    }
}
