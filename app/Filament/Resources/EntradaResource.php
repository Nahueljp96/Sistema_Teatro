<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntradaResource\Pages;
use App\Filament\Resources\EntradaResource\RelationManagers;
use App\Models\Entrada;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Obra;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntradaResource extends Resource
{
    protected static ?string $model = Entrada::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
           
            return $form
            ->schema([
                Forms\Components\Select::make('obra_id')
                    ->relationship('obra', 'titulo') // Relación con el modelo Obra y muestra el título
                    ->required()
                    ->label('Obra'),

                Forms\Components\TextInput::make('comprador_email')
                    ->email()
                    ->required()
                    ->label('Email del comprador')
                    ->maxLength(255),

                Forms\Components\TextInput::make('nombre_comprador')
                    ->required()
                    ->label('Nombre del comprador')
                    ->maxLength(255),

                Forms\Components\TextInput::make('telefono')
                    ->required()
                    ->label('Teléfono')
                    ->maxLength(20),

                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric()
                    ->label('Cantidad'),

                Forms\Components\Select::make('estado_pago')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagado' => 'Pagado',
                        'fallido' => 'Fallido',
                    ])
                    ->required()
                    ->label('Estado del pago'),

                Forms\Components\TextInput::make('preference_id')
                    ->label('Preference ID')
                    ->maxLength(255),
            ]);    
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('obra.titulo') // Relación con Obra para mostrar el título
                    ->label('Obra')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('comprador_email')
                    ->label('Email del comprador')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nombre_comprador')
                    ->label('Nombre del comprador')
                    ->searchable(),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('estado_pago')
                    ->label('Estado del pago')
                    ->sortable(),

                Tables\Columns\TextColumn::make('preference_id')
                    ->label('Preference ID'),

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
                // Puedes agregar filtros personalizados aquí
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEntradas::route('/'),
            'create' => Pages\CreateEntrada::route('/create'),
            'edit' => Pages\EditEntrada::route('/{record}/edit'),
        ];
    }
}
