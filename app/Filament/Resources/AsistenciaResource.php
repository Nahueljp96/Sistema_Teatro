<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsistenciaResource\Pages;
use App\Filament\Resources\AsistenciaResource\RelationManagers;
use App\Models\Asistencia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\BooleanColumn;

use Filament\Forms\Components\{TextInput, DatePicker, Select, Toggle};
use Filament\Tables\Columns\TextColumn;

class AsistenciaResource extends Resource
{
    protected static ?string $model = Asistencia::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form->schema([
        Select::make('alumno_id')
            ->relationship('alumno', 'nombre')
            ->required(),
        Select::make('curso_id')
            ->relationship('curso', 'nombre')
            ->required(),
        DatePicker::make('fecha_asistencia')->required(),
        Toggle::make('presente')->label('¿Presente?'),
    ]);
}

public static function table(Table $table): Table
{
    return $table->columns([
        TextColumn::make('alumno.nombre')->label('Alumno'),
        TextColumn::make('curso.nombre')->label('Curso'),
        TextColumn::make('fecha_asistencia')->date('d/m/Y'),
        TextColumn::make('presente')
            ->label('Presente')
            ->icon(function ($record) {
                return $record->presente ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
            })
            ->color(function ($record) {
                return $record->presente ? 'success' : 'danger';
            }),
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
            'index' => Pages\ListAsistencias::route('/'),
            'create' => Pages\CreateAsistencia::route('/create'),
            'edit' => Pages\EditAsistencia::route('/{record}/edit'),
        ];
    }
}
