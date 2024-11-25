<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AltaResource\Pages;
use App\Filament\Resources\AltaResource\RelationManagers;
use App\Models\Alta;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;

class AltaResource extends Resource
{
    protected static ?string $model = Alta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
        {
            return $form
                ->schema([
                    Forms\Components\Select::make('alumno_id')->label('Alumno')
                        ->relationship('alumno', 'nombre')
                        ->required(),  
                    Forms\Components\Select::make('curso_id')->label('Curso')
                        ->relationship('curso', 'nombre')
                        ->required(),
                    Forms\Components\Toggle::make('pago_al_dia')
                        ->required(),

                    DatePicker::make('fecha_alta')
                    ->format('Y/m/d')
                    ->required(),     
                ]);
        }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('alumno.nombre')
                    ->label('Alumno'),
                Tables\Columns\TextColumn::make('curso.nombre')
                     ->label('Curso'),
                Tables\Columns\TextColumn::make('alumno.email')
                     ->label('Email'),
                Tables\Columns\IconColumn::make('pago_al_dia')
                    ->boolean(),
                Tables\Columns\TextColumn::make('fecha_alta')
                    ->label('Fecha de Alta')
                    ->date(),    
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
            'index' => Pages\ListAltas::route('/'),
            'create' => Pages\CreateAlta::route('/create'),
            'edit' => Pages\EditAlta::route('/{record}/edit'),
        ];
    }
}
